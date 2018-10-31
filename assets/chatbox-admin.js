(function ($) {

	var chatBox;
	var config;
	var intervalRefresh;
	var isBusy = false;
	var momentInterval;

	$.fn.chatBox = function(options) {
		chatBox = this;
		config = options;

		initializeUI();
		initializeContact();

		activateEvent();

		momentInterval = setInterval(function(){
			$('.moment').each(function(){
				$(this).text(moment($(this).data('time')).fromNow());
			});
		}, config.momentInterval);

		status.get();
	}

	var status = {
		get: function(){
			var self = this;

			$.ajax({
				url: config.api.status,
				method: 'get',
				dataType: 'json',
				success: function(result){
					self.change(result.data);
				}
			});
		},
		request: function(status){
			var self = this;
			var temp = $(chatBox).find('#profile-img').attr('class');
			var status = ($(status).attr('id')).slice(7);
			self.change(status);

			$.ajax({
				url: config.api.status,
				method: 'post',
				dataType: 'json',
				data: {
					status: status
				},
				error: function(){
					self.change(temp);
				}
			});
		},
		change: function(status){
			if(this.hasClass(status)){
				$(chatBox).find('#profile-img').removeClass();
				$(chatBox).find('#profile-img').addClass(status);
			}
		},
		hasClass: function(status){
			if($(chatBox).find('#profile-img').hasClass('online')){
				return true;
			}
			else if($(chatBox).find('#profile-img').hasClass('away')){
				return true;
			}
			else if($(chatBox).find('#profile-img').hasClass('busy')){
				return true;
			}
			else if($(chatBox).find('#profile-img').hasClass('offline')){
				return true;
			}
			else{
				return false;
			};
		},
		initialize: function(){
			this.get();
		}
	}

	var contact = {
		interval: undefined,
		parse: function(data){
			var html = $(node.contact).data('id', data.id).attr('id', 'contact-'+data.id).find('.name').text(data.name).siblings('.preview').text(data.msg).closest('li');
			if(data.isBlocked == "1"){
				$(html).addClass('blocked');
			}
			this.inject(html);
		},
		inject: function(html){
			$(chatBox).find('#contacts ul').append(html);
		},
		sendAjax: function(last, isFirst){
			var self = this;
			$.ajax({
				url: config.api.contact,
				method: 'post',
				dataType: 'json',
				data: {
					last: last
				},
				success: function(result){
					if(result.length > 0){
						$.each(result, function(i, data){
							self.parse(data);
						});

						if(isFirst == true){
							if(result.length == config.limit){
								$(chatBox).find('#contacts').append('<a href="#" class="btn-link" id="loadMoreContact">Muat lebih banyak kontak</a>')
							}
							chat.open($(chatBox).find('.contact').get(0));
						}
					}
					else{
						$(chatBox).find('#loadMoreContact').fadeOut();
						if(isFirst == true){
							setTimeout(function(){
								self.sendAjax(undefined, true);
							}, config.refreshInterval);
						}
					}
				}
			});
		},
		refreshNew: function(active){
			var self = this;

			$.ajax({
				url: config.api.chat,
				method: 'post',
				dataType: 'json',
				data: {
					except: active
				},
				success: function(result){
					result = result.reverse();
					$.each(result, function(i, data){
						self.parseNew(data);
					});
				}
			});
		},
		parseNew: function(data){
			var current = $('#contact-'+data.id);
			if(current.length > 0){
				if(current.find('.badge').length > 0){
					if(current.find('.preview').html() != $('<p></p>').text(data.msg).html){
						$(current).find('.preview').text(data.msg);
					}
				}
				else{
					current.hide().find('.meta').prepend(node.badge).siblings('.preview').text(data.msg);
					$(current).fadeOut('slow', function(){
						$(current).prependTo($(chatBox).find('#contacts ul')).fadeIn();
					});
				}
			}
			else{
				var html = $(node.contact).data('id', data.id).attr('id', 'contact-'+data.id).find('.meta').prepend(node.badge).find('.name').text(data.name).siblings('.preview').text(data.msg).closest('li');
				$(chatBox).find('#contacts ul').prepend(html);
			}
		},
		startInterval: function(active){
			var self = this;
			self.interval = setInterval(function(){
				self.refreshNew(active);
			}, config.refreshInterval);
		},
		clearInterval: function(){
			clearInterval(this.interval);
		}
	}

	var chat = {
		active: undefined,
		interval: undefined,
		open: function(el){
			this.active = $(el).data('id');
			$(el).find('.badge').fadeOut('fast', function() {
				$(el).find('badge').remove();
			});
			this.stopRefresh();
			this.destroyUI();
			this.loadUI(el);
			$(el).addClass('active');
			this.loadNewer(true);
			contact.refreshNew(this.active);
			contact.startInterval(this.active);
		},
		getHistory: function(firstEl, isFirst){
			var self = this;
	
			$.ajax({
				url: config.api.history,
				method: 'post',
				dataType: 'json',
				data: {
					last: firstEl.data('id'),
					id: self.active
				},
				success: function(result){
					if(result.length > 0){
						$.each(result, function(i, data){
							if(data.source == 'client'){
								var html = $(node.reply).data('id', data.id).find('p').text(data.msg).siblings('span').data('time', data.waktu).text(moment(data.waktu).fromNow()).parent();
								$(chatBox).find('.messages ul').prepend(html);
							}
							else if(data.source == 'admin'){
								var html = $(node.sent).data('id', data.id).find('p').text(data.msg).siblings('span').data('time', data.waktu).text(moment(data.waktu).fromNow()).parent();
								$(chatBox).find('.messages ul').prepend(html);
							}
						});

						if(isFirst == true){
							if(result.length == config.limit){
								$(chatBox).find('.messages').prepend(node.loadHistoryChat);
							}
							if(!$(chatBox).find('#contact-'+chat.active).hasClass('blocked')){
								self.startRefresh();
							}
							self.scrollToEnd();
						}
						else{
							if(firstEl.offset() != undefined){
								var scroll = $(chatBox).find('.contact-profile').height() - $(chatBox).find('#loadHistoryChat').height();
								$(firstEl).prevAll().each(function(){
									scroll += $(this).outerHeight();
								});
								self.scrollTo(scroll);
							}
						}
					}
					if(result.length < config.limit){
						$(chatBox).find('#loadHistoryChat').fadeOut();
					}
				}
			});
		},
		loadUI: function(el){
			var box = $(node.content);
			if($(el).hasClass('blocked')){
				$(box).find('#block').remove();
				$(box).find('.message-input').html(node.disabled);
			}
			$(box).appendTo(chatBox).find('.contact-profile p').text($(el).find('.name').text());
		},
		destroyUI: function(){
			$(chatBox).find('.contact').removeClass('active');
			$(chatBox).find('.content').remove();
			contact.clearInterval();
		},
		loadNewer: function(isFirst){
			var self = this;
			$.ajax({
				url: config.api.replies,
				method: 'post',
				dataType: 'json',
				data: {
					id: self.active
				},
				success: function(result){
					if(!result.valid){
						self.block(true);
					}
					self.newChat(result.data.length);
					$.each(result.data, function(i, data){
						if(data.source == 'client'){
							var html = $(node.reply).data('id', data.id).find('p').text(data.msg).siblings('span').data('time', data.waktu).text(moment(data.waktu).fromNow()).parent();
							$(chatBox).find('.messages ul').append(html);
						}
						else if(data.source == 'admin'){
							var html = $(node.sent).data('id', data.id).find('p').text(data.msg).siblings('span').data('time', data.waktu).text(moment(data.waktu).fromNow()).parent();
							$(chatBox).find('.messages ul').append(html);
						}
					});
					if(isFirst == true){
						self.getHistory($(chatBox).find('.messages li:not(#newChat)').first(), isFirst);
					}
					else{
						self.scrollToEnd();
					}
				}
			});
		},
		newMessage(){
			var message = $(chatBox).find('.message-input input').val();
			var self = this;
			
			if($.trim(message) != ''){
				var time = moment().format('YYYY-MM-DD HH:mm:ss');
				var output = $(node.sent).data('id', '').addClass('loading').find('p').text(message).siblings('span').data('time', time).text(moment(time).fromNow()).parent();
				$(chatBox).find('.messages ul').append(output);
				$(chatBox).find('.message-input input').val('');

				$.ajax({
					url: config.api.send,
					method: 'post',
					dataType: 'json',
					data: {
						id: self.active,
						msg: message
					},
					success: function(result){
						if(result.status){
							$(output).data('id', result.id).removeClass('loading');
							self.scrollToEnd();
							$(chatBox).find('#contact-'+self.active).find('.preview').text(message);
						}
						else{
							$(output).find('span').addClass('text-danger').text('Tidak terkirim');
						}
					},
					error: function(){
						$(output).find('span').addClass('text-danger').text('Tidak terkirim');
					}
				});
			}
		},
		scrollToEnd: function(){
			$(chatBox).find('.messages').scrollTop($(chatBox).find('.messages').height()+100);
		},
		scrollTo: function(offset){
			$(chatBox).find('.messages').scrollTop(offset);
		},
		startRefresh: function(){
			var self = this;
			self.interval = setInterval(function(){
				self.loadNewer();
			}, config.refreshInterval);
		},
		stopRefresh: function(){
			clearInterval(this.interval);
		},
		newChat: function(num){
			if(num > 0){
				this.clearNewChat();
				var output = $(node.newChat).text(num + " pesan baru");
				$('.messages ul').append(output);
			}
		},
		clearNewChat: function(){
			$(chatBox).find('#newChat').remove();
		},
		block: function(isAlreadyBlocked){
			var self = this;

			if(isAlreadyBlocked != true){
				$.ajax({
					url: config.api.block,
					method: 'post',
					dataType: 'json',
					data: {
						id: self.active
					},
					success: function(result){
						$(chatBox).find('#contact-'+result.id).addClass('blocked');
						$(chatBox).find('#block').remove();
						$(chatBox).find('.message-input').html(node.disabled);
						self.stopRefresh();
					}
				});
			}
		},
		delete: function(){
			var self = this;

			$.ajax({
				url: config.api.delete,
				method: 'post',
				dataType: 'json',
				data: {
					id: self.active
				},
				success: function(result){
					$(chatBox).find('#contact-'+result.id).slideUp('fast', function(){
						$(chatBox).find('#contact-'+result.id).remove();
					});
					self.stopRefresh();
					self.open($(chatBox).find('.contact').not('#contact-'+result.id).first());
				}
			});
		}
	}

	function initializeUI(){
		$(chatBox).html(node.wrapper);
	}

	function initializeContact(){
		contact.sendAjax(undefined, true);
	}

	var node = {
		reply: '<li class="replies"><span class="moment"></span><p></p></li>',
		sent: '<li class="sent"><span class="moment"></span><p></p></li>',
		contact: '<li class="contact"><div class="wrap"><div class="meta"><p class="name">Louis Litt</p><p class="preview">You just got LITT up, Mike.</p></div></div></li>',
		content: '<div class="content"><div class="contact-profile"><button type="button" id="toggleNav" class="btn btn-light bg-transparent btn-sm float-left"><i class="fa fa-bars"></i></button><p></p><button type="button" class="btn btn-danger btn-sm" id="block" title="Akhiri percakapan"><i class="fa fa-ban"></i></button><button type="button" class="btn btn-danger btn-sm" id="delete" title="Hapus Percakapan"><i class="fa fa-trash"></i></button></div><div class="messages"><ul></ul></div><div class="message-input"><div class="wrap"><input type="text" placeholder="Tulis pesanmu..." /><button class="submit"><i class="fa fa-paper-plane" aria-hidden="true"></i></button></div></div></div>',
		wrapper: '<div id="sidepanel"><div id="profile"><div class="wrap"><img id="profile-img" src="../assets/images/avatar.png" class="online" alt="" /><p>Admin</p><div id="status-options"><ul><li id="status-online" class="active"><span class="status-circle"></span><p>Online</p></li><li id="status-away"><span class="status-circle"></span><p>Away</p></li><li id="status-busy"><span class="status-circle"></span><p>Busy</p></li><li id="status-offline"><span class="status-circle"></span><p>Offline</p></li></ul></div></div></div><div id="contacts"><ul></ul></div></div>',
		loadMoreContact: '<a href="#" class="btn-link" id="loadMoreContact">Muat lebih banyak kontak</a>',
		loadHistoryChat: '<a href="#" class="btn-link" id="loadHistoryChat">Muat sebelumnya</a>',
		newChat: '<li href="#" class="btn-link" id="newChat"></li>',
		badge: '<span class="badge badge-primary">NEW</span>',
		search: '<div id="search"><label for=""><i class="fa fa-search" aria-hidden="true"></i></label><input type="text" placeholder="Cari Pesan" /></div>',
		disabled: '<p class="text-center"><i>Tidak bisa mengirim pesan.</i></p>'
	}

	// END OF CODE

	function activateEvent(){
		$(chatBox).on('click', '#toggleNav', function(event) {
			event.preventDefault();

			$(chatBox).find('#sidepanel').toggleClass('open');
		});

		$('.messages').animate({
			scrollTop: $(document).height()
		}, 'fast');

		$(chatBox).on('click', '#profile-img', function(){
			$('#status-options').toggleClass('active');
		});

		$(chatBox).on('click', '#status-options ul li', function(){
			status.request(this);
			$('#status-options').toggleClass('active');
		});
		
		$(chatBox).on('click', '#loadMoreContact', function(event){
			event.preventDefault();

			var last = $(chatBox).find('#contacts .contact').last().data('id');
			contact.sendAjax(last);
		});

		$(chatBox).on('click', '#loadHistoryChat', function(event){
			event.preventDefault();

			var last = $(chatBox).find('.messages > ul > li').first();
			chat.getHistory(last);
		});

		$(chatBox).on('click', '.contact:not(.active)', function(){
			chat.open(this);
		})

		$(chatBox).on('click', '.submit', function(){
			chat.newMessage();
		});

		$(chatBox).on('click', '#block', function(){
			swal({
				text: 'Apakah kamu yakin ingin mengakhiri percakapan?',
				icon: 'error',
				dangerMode: true,
				buttons: true
			}).then((value) => {
				if(value == true){
					chat.block();
				}
			});
		});

		$(chatBox).on('click', '#delete', function(){
			swal({
				text: 'Apakah kamu yakin ingin menghapus percakapan?',
				icon: 'error',
				dangerMode: true,
				buttons: true
			}).then((value) => {
				if(value == true){
					chat.delete();
				}
			});
		});

		$(chatBox).on('keydown', '.message-input input', function(e){
			if (e.which == 13){
				chat.newMessage();
				return false;
			}
		});

	}

}(jQuery));