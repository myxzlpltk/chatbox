(function ($) {

	var chatBox = this;
	var isBusy = false;
	var config = {};

	$.fn.chatBox = function(options) {

		chatBox = this;
		config = options;

		UI.initialize();

		if(sessionStorage.getItem(config.sessionIndex) != undefined){
			UI.startChatBox();
		}

		UI.updateStatus(config.status);
		UI.startIntervalUpdateStatus();
		
		UI.activateEvent();
	}

	var UI = {
		status: '',
		initialize: function(){
			if(navigator.maxTouchPoints > 0 && $(document).width() <= 768){
				$(node.card).addClass('mobile').appendTo(chatBox);
			}
			else{
				$(chatBox).html(node.card);
			}
		},
		startIntervalUpdateStatus: function(){
			var self = this;

			sendAjax();
			
			setInterval(function(){
				sendAjax();
			}, config.updateStatusInterval);

			function sendAjax(){
				$.ajax({
					url: config.api.status,
					method: 'get',
					dataType: 'json',
					success: function(result){
						self.updateStatus(result.data);
					}
				});
			}
		},
		updateStatus: function(status){
			switch(status){
				case 'online':
					this.status = 'bg-success';
					break;

				case 'busy':
					this.status = 'bg-danger';
					break;

				case 'away':
					this.status = 'bg-warning';
					break;

				case 'offline':
					this.status = 'bg-secondary';
					break;

				default:
					this.status = 'bg-primary';
					break;
			}

			$(chatBox).find('#main-card > .card-header #statusAdmin').removeClass();
			$(chatBox).find('#main-card > .card-header #statusAdmin').addClass('status '+this.status);
			$(chatBox).find('#main-card > .card-header #statusAdmin').attr('title', status);
			$(chatBox).find('#main-card > .card-header #statusAdmin').tooltip();
		},
		showForm: function(){
			var form = $(node.formRequest);
			$(form).attr('action', config.api.request);
			$(chatBox).find('#main-card > .card-body').html(form).slideDown('slow', function(){
				$(chatBox).find('#main-card > .card-header .header-content').slideUp();
			});
		},
		destroyForm: function(){
			$(chatBox).find('form#startChatBox').fadeOut('fast', function(){
				$(chatBox).find('form#startChatBox').remove();
			});
		},
		startChatBox: function(){
			var box = $(node.chatBox)
			$(chatBox).find('#main-card > .card-header .header-content').hide();
			$(chatBox).find('#main-card > .card-header .greet').hide();
			$(box).find('#formMessage').attr('action', config.api.send);
			$(chatBox).find('#main-card').append(box).find('.card-body').html(node.mainChat).slideDown();
			$(chatBox).find('#main-card > .card-header').prepend(node.destroyBtn);

			Chat.loadHistory(undefined, true);
			Chat.startInterval();
		},
		activateEvent: function(){
			__activateEvent();
		},
		destroyApp: function(){
			Chat.stopInterval();
			sessionStorage.clear(config.sessionIndex);
			$(chatBox).find('#destroyChats').remove();
			$(chatBox).find('#main-card > .card-footer').remove();
			UI.destroyForm();
			UI.showForm();

			swal({
				text: config.thankYouDict,
				icon: 'success'
			});
		},
		openChatBox: function(){
			$(chatBox).find('#main-card').slideDown();
			$(chatBox).find('.toggleChat').children('i').removeClass('fa-comment');
			$(chatBox).find('.toggleChat').children('i').addClass('fa-close');
			$(chatBox).children('#chats-box').removeClass('hide');
		},
		closeChatBox: function(){
			$(chatBox).find('#main-card').slideUp();
			$(chatBox).find('.toggleChat').children('i').addClass('fa-comment');
			$(chatBox).find('.toggleChat').children('i').removeClass('fa-close');
			$(chatBox).children('#chats-box').addClass('hide');
		}
	};

	var Chat = {
		interval: undefined,
		startInterval: function(){
			var self = this;
			interval = setInterval(function(){
				self.getNew();
			}, config.refreshInterval);
		},
		stopInterval: function(){
			clearInterval(interval);
		},
		scrollToEnd: function(){
			var total = 100;
			$(chatBox).find('#main-chat').children().each(function(){
				total += $(this).height();
			});
			$(chatBox).find('#main-chat').scrollTop(total);
		},
		keepScroll: function(last){
			var total = 0;
			total -= $(chatBox).find('#loadOlder').height();
			$(last).prevAll().each(function(){
				total += $(this).outerHeight();
			});
			$(chatBox).find('#main-chat').scrollTop(total);
		},
		getNew: function(){
			var self = this;

			$.ajax({
				url: config.api.replies,
				method: 'post',
				dataType: 'json',
				data: {
					token: sessionStorage.getItem(config.sessionIndex)
				},
				success: function(result){
					if(result.status){
						$.each(result.data, function(index, el) {
							self.inject(self.parse(el), 'append');
						});
						if(result.data.length > 0){
							self.scrollToEnd();
						}
					}
					else{
						if(result.validated == false){
							UI.destroyApp();
						}
					}
				}
			});
		},
		loadHistory: function(last, isFirst){
			var self = this;
			$.ajax({
				url: config.api.history,
				method: 'post',
				dataType: 'json',
				data: {
					token: sessionStorage.getItem(config.sessionIndex),
					last: last != undefined ? last.data('id') : undefined
				},
				success: function(result){
					if(result.status){
						if(result.data.length >= config.limit){
							if(isFirst == true){
								$(chatBox).find('#main-chat').html(node.loadOlder);
							}
						}
						else{
							$(chatBox).find('#main-chat #loadOlder').fadeOut('fast', function(){
								$(chatBox).find('#main-chat #loadOlder').remove();
							});
						}

						$.each(result.data, function(index, el) {
							self.inject(self.parse(el), 'prepend', isFirst);
						});

						if(isFirst == true){
							self.scrollToEnd();
							UI.openChatBox();
						}
						else{
							self.keepScroll(last);
						}
					}
					else{
						if(result.validated == false){
							UI.destroyApp();
						}
						else{
							swal({
								text: 'Terjadi kesalahan',
								icon: 'error'
							});
						}
					}
				},
				error: function(xhr){
					swal({
						text: 'Gagal mengirim request',
						icon: 'error'
					});
				}
			});
		},
		newMessage: function(data){
			data.message = $.trim(data.message);
			if(data.message != ''){
				var msg = this.prepareNewMessage(data.message);

				$.ajax({
					url: config.api.send,
					method: 'post',
					dataType: 'json',
					data: {
						message: data.message,
						token: sessionStorage.getItem(config.sessionIndex)
					},
					success: function(result){
						if(result.status){
							$(msg).data('id', result.id);
							$(msg).removeClass('loading');
						}
						else{
							if(result.validated == false){
								UI.destroyApp();
							}
							else{
								$(msg).find('.time').addClass('text-danger');
								$(msg).find('.time').html('Tidak terkirim');
							}
						}
					},
					error: function(xhr){
						$(msg).find('.time').addClass('text-danger');
						$(msg).find('.time').html('Tidak terkirim');
					},
					complete: function(){
						$(chatBox).find('#inputMessage').val('');
					}
				});
			}
		},
		prepareNewMessage: function(msg){
			var temp = {
				source: 'client',
				waktu: moment().format('YYYY-MM-DD HH:mm:ss'),
				data: msg,
				id: ''
			};
			msg = $(this.parse(temp)).addClass('loading');

			msg = this.inject(msg, 'append');

			this.scrollToEnd();
			return $(msg);
		},
		inject: function(msg, method, isFirst){
			switch(method){
				case 'append':
					$(chatBox).find('#main-chat').append(msg);
					break;

				case 'prepend':
					if($(chatBox).find('#main-chat #loadOlder').length > 0){
						$(chatBox).find('#main-chat #loadOlder').after(msg)
					}
					else{
						$(chatBox).find('#main-chat').prepend(msg);
					}
					break;
			}

			return $(msg);
		},
		parse: function(data){
			var message;
			if(data.source == 'client'){
				message = $(node.sent);
			}
			else if(data.source == 'admin'){
				message = $(node.reply);
			}
			else{
				return '';
			}

			$(message)
				.data('id', data.id)
				.find('.msg')
				.text(data.data)
				.siblings('.time')
				.data('time', data.waktu)
				.text(moment(data.waktu).fromNow())
				.closest('li');

			return $(message);
		}	
	};

	var node = {
		formRequest: '<form action="" method="post" accept-charset="utf-8" id="startChatBox"><div class="p-3"><div class="form-group"><input type="text" name="nama" class="form-control form-control-sm" placeholder="Nama Lengkap" required></div><div class="form-group"><input type="text" name="email" class="form-control form-control-sm" placeholder="Alamat Email" required></div><div class="form-group"><input type="text" name="phone" class="form-control form-control-sm" placeholder="Nomor Telepon" required></div><div class="form-group"><textarea name="message" class="form-control form-control-sm" placeholder="Isi Pesan" rows="3" style="resize: none;"></textarea></div><button type="submit" class="btn btn-primary btn-sm btn-block">Mulai</button></div></form>',
		chatBox: '<div class="card-footer p-0"><form action="" method="post" accept-charset="utf-8" id="formMessage"><div class="input-group"><input type="text" name="message" class="form-control" id="inputMessage" placeholder="Ketik pesan" autocomplete="off"><div class="input-group-append"><button type="submit" id="sendMessage" class="btn btn-sm my-1 mr-1"><i class="fa fa-send"></i></button></div></div></form></div>',
		mainChat: '<ul id="main-chat"></ul>',
		loadOlder: '<a href="#" class="btn-link mt-2" id="loadOlder">Muat sebelumnya</a>',
		sent: '<li class="right" data-id=""><span class="time moment" data-time=""></span><span class="msg"></span></li>',
		reply: '<li class="left" data-id=""><span class="time moment" data-time=""></span><span class="msg"></span></li>',
		card: '<div id="chats-box" class="hide"><div class="card" id="main-card" style="display: none;"><div class="card-header"> <div class="header-title"><button id="goBack" class="btn btn-primary"><i class="fa fa-angle-left"></i></button><i id="statusAdmin" class="status bg-success" title="online"></i><p class="m-0 text-white d-inline" title="online">Customer Service</p></div> <div class="greet"><h3>Hi there <i class="fa fa-hand-stop-o"></i></h3><p class="lead">Ask us anything you want</p></div> <div class="header-content py-3"><button type="button" id="startNewChatBox" class="btn btn-outline-light btn-sm"><i class="fa fa-send"></i> <span>New Conversation</span></button></div> </div><div class="card-body p-0" style="display: none;"></div></div><div class="pull-right"><div class="toggleChat"><i class="fa fa-comment"></i></div></div></div>',
		destroyBtn: '<button type="button" class="close py-2" id="destroyChats"><i class="fa fa-close fa-lg"></i></button>',
	}

	function __activateEvent(){
		$(window).resize(function(){
			console.log('me');
			if(navigator.maxTouchPoints > 0 && $(document).width() <= 768){
				$(chatBox).find('#chats-box').addClass('mobile');
			}
			else{
				$(chatBox).find('#chats-box').removeClass('mobile');
			}
		});

		$(chatBox).on('click', '#goBack', function(event){
			UI.closeChatBox();
		});

		$(chatBox).on('click', '#startNewChatBox', function(event){
			event.preventDefault();
			UI.showForm();
		});

		$(chatBox).on('submit', 'form#startChatBox', function(event){
			event.preventDefault();

			swal({
				text: 'Apa kamu ingin memulai percakapan?',
				icon: 'info',
				buttons: true
			}).then((value) => {
				if(value == true){
					var data = serialize(this);

					$.ajax({
						url: config.api.request,
						method: 'post',
						dataType: 'json',
						data: data,
						success: function(result){
							if(result.status){
								sessionStorage.setItem(config.sessionIndex, result.token);
								UI.destroyForm();
								UI.startChatBox();
							}
							else{
								swal({
									text: 'Gagal mendapatkan token: '+result.error,
									icon: 'error'
								});
							}
						},
						error: function(xhr){
							swal({
								text: 'Gagal mengirim request',
								icon: 'error'
							});
						}
					});
				}
			});
		});

		$(chatBox).on('click', '#loadOlder', function(event){
			event.preventDefault();
			Chat.loadHistory($(chatBox).find('#main-chat li').first());
		});

		$(chatBox).on('submit', 'form#formMessage', function(event){
			event.preventDefault();

			var data = serialize(this);

			Chat.newMessage(data);
		});

		$(chatBox).on('click', '#destroyChats', function(event){
			event.preventDefault();

			swal({
				text: 'Apakah kamu yakin untuk mengakhiri sesi?',
				dangerMode: true,
				buttons: true
			}).then((value) => {
				if(value == true){
					$.ajax({
						url: config.api.destroy,
						method: 'post',
						dataType: 'json',
						data: {
							token: sessionStorage.getItem(config.sessionIndex)
						},
						success: function(result){
							if(result.status){
								UI.destroyApp();
							}
							else{
								if(result.validated == false){
									UI.destroyApp();
								}
								else{
									swal({
										text: 'Terjadi kesalahan',
										icon: 'error'
									});
								}
							}
						},
						error: function(){
							swal({
								text: 'Terjadi kesalahan',
								icon: 'error'
							});
						}
					});
				}
			});
		});
		
		$(chatBox).on('click', '.toggleChat', function(){
			if($(this).children('i').hasClass('fa-close')){
				UI.closeChatBox();
			}
			else if($(this).children('i').hasClass('fa-comment')){
				UI.openChatBox();
			}
			else{
				swal({
					text: 'Terjadi kesalahan',
					icon: 'error'
				});
			}
		});

		setInterval(function(){
			$(chatBox).find('.moment').each(function(){
				$(this).html(moment($(this).data('time')).fromNow());
			});
		}, 30000);
	}
	
	function serialize(form){
		var data = $(form).serializeArray();
		var result = {};
		$.each(data, function(index, el) {
			result[el.name] = el.value;
		});
		return result;
	}



}(jQuery));