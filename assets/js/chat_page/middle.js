var video_preview = null;
var group_header_contents = null;
var load_group_header_request = null;

const userID = $(".main .chatbox .header .icons .userId").text();
const userName = $(".main .chatbox .header .icons .userName").text();
const appID = 943814233;
const serverSecret = "54845a5afc66a4ef38c90771b6b4be8e";
const TOKEN = ZegoUIKitPrebuilt.generateKitTokenForTest(appID, serverSecret, null, userID, userName);

const zp = ZegoUIKitPrebuilt.create(TOKEN);
zp.addPlugins({ ZIM });
zp.setCallInvitationConfig({
    ringtoneConfig: {
      incomingCallUrl: 'assets/files/ringtones/incoming.mp3', // The ringtone when receiving an incoming call invitation.
      outgoingCallUrl: 'assets/files/ringtones/outgoing.mp3' // The ringtone when sending a call invitation. 
    },
    // The callback for the call invitation ends (this will be triggered when the call invitation is refused/timed out/canceled/ended due to busy status.)
    onCallInvitationEnded: (reason,data) =>{
        // Add your custom logic here.
        var endTime = new Date();
        var timeDiff = endTime - startTime;
  
        // Convert the time difference to minutes
        var minutesDiff = Math.ceil(timeDiff / 1000 / 60);
    },
    // The callback for the call invitation is accepted before joining the room (a room is used for making a call), which can be used to set up the room config. The Call Kit enables you to join the room automatically, and the room config adapts according to the specific call type (ZegoInvitationType).
    onSetRoomConfigBeforeJoining: (callType) => {
      startTime = new Date();
      elapsedTime = 0;
    },
    // The callee will receive the notification through this callback when receiving a call invitation.
    onIncomingCallReceived: (callID, caller, callType, callees) => {
      console.log("onIncomingCallReceived");
    },
  
    // The callee will receive the notification through this callback when the caller canceled the call invitation.  
    onIncomingCallCanceled: (callID, caller) => {
      console.log("onIncomingCallCanceled");
    },
  
    // The callee will receive the notification through this callback when the caller accepts the call invitation. 
    onOutgoingCallAccepted: (callID, callee) => {
      console.log("onOutgoingCallAccepted");
      
    },
  
    // The caller will receive the notification through this callback when the callee is on a call.
    onOutgoingCallRejected: (callID, callee) => {
      console.log("onOutgoingCallRejected");
    },
  
    // The caller will receive the notification through this callback when the callee declines the call invitation. 
    onOutgoingCallDeclined: (callID, callee) => {
      console.log("onOutgoingCallDeclined");
    },
  
    // The callee will receive the notification through this callback when he didn't respond to the call invitation. 
    onIncomingCallTimeout: (callID, caller) => {
      console.log("onIncomingCallTimeout");
    },
  
    // The caller will receive the notification through this callback when the call invitation timed out.
    onOutgoingCallTimeout: (callID, callees) => {
      console.log("onOutgoingCallTimeout");
    }
  })
  
$("body").on('click', '.load_page', function (e) {

    if (!$(this).hasClass('processing')) {

        $(this).addClass('processing');
        open_column('second');

        var browser_title = default_meta_title;
        var browser_address_bar = baseurl;
        var element = $(this);

        if ($(this).attr('loader') !== undefined) {
            $($(this).attr('loader')).show();
        }

        $('.main .middle > .content > div').addClass('d-none');
        $('.main .middle > .content > .custom_page').removeClass('d-none');
        $('.main .middle > .content > .custom_page > .page_content').hide();
        $('.main .middle > .content > .custom_page > .page_content > div').html('');

        var data = {
            load: 'custom_page_content',
            page_id: $(this).attr('page_id'),
        };

        if (user_csrf_token !== null) {
            data["csrf_token"] = user_csrf_token;
        }

        $.ajax({
            type: 'POST',
            url: api_request_url,
            data: data,
            async: true,
            success: function (data) { }
        }).done(function (data) {
            if (data === '') {
                location.reload(true);
            } else if (isJSON(data)) {
                data = $.parseJSON(data);


                if (data.browser_title !== undefined) {
                    browser_title = data.browser_title;
                }

                if (data.browser_address_bar !== undefined) {
                    browser_address_bar = data.browser_address_bar;
                }

                if (data.title != undefined) {
                    $('.main .middle > .content > .custom_page > .header > .left > .title').replace_text(data.title);
                }

                if (data.subtitle != undefined) {
                    $('.main .middle > .content > .custom_page > .header > .left > .sub_title').replace_text(data.subtitle);
                } else {
                    $('.main .middle > .content > .custom_page > .header > .left > .sub_title').replace_text('');
                }

                if (data.page_content != undefined) {
                    $('.main .middle > .content > .custom_page > .page_content > div').html(data.page_content);
                    $('.main .middle > .content > .custom_page > .page_content').show();
                }
            } else {
                console.log('ERROR : ' + data);
            }
            if (element.attr('loader') !== undefined) {
                $(element.attr('loader')).hide();
            }
            element.removeClass('processing');

            change_browser_title(browser_title);

            history.pushState({}, null, browser_address_bar);

        }).fail(function (qXHR, textStatus, errorThrown) {
            if (element.attr('loader') !== undefined) {
                $(element.attr('loader')).hide();
            }
            element.removeClass('processing');
            console.log('ERROR : ' + errorThrown);
        });
    }
});


$("body").on('mouseenter', '.main .chatbox > .header.view_info > .heading,.main .chatbox > .header.view_info > .image', function (e) {
    if ($(window).width() > 767.98) {
        $('.main .chatbox > .header > .heading > .subtitle').hide();
        $('.main .chatbox > .header > .heading > .whos_typing').hide();
        $('.main .chatbox > .header > .heading > .view_info').fadeIn();
    }
});

$("body").on('mouseleave', '.main .chatbox > .header.view_info > .heading,.main .chatbox > .header.view_info > .image', function (e) {
    if ($(window).width() > 767.98) {
        $('.main .chatbox > .header > .heading > .view_info').hide();
        $('.main .chatbox > .header > .heading > .subtitle').fadeIn();
        $('.main .chatbox > .header > .heading > .whos_typing').fadeIn();
    }
});

$("body").on('click', '.toggle_search_messages', function (e) {
    if ($('.main .middle .search_messages').is(':visible')) {
        $('.main .middle .search_messages').hide();
    } else {
        $('.main .chatbox > .header > .switch_user').removeClass('open');
        $('.main .middle .search_messages').fadeIn();
        $('.main .middle .search_messages > div > .search > div > input').trigger('focus');
    }
});
//FIXME-BINGO
$("body").on('click', '.do_voice_call', async function (e) {

    if ($(".main .chatbox").attr('group_id') !== undefined) {
        var data = {
            load: "group_members",
            filter: 0,
            offset: 0,
            sortby: 0,
            search: 0,
            group_id:$(".main .chatbox").attr('group_id')
        };
        
        if (user_csrf_token !== null) {
            data["csrf_token"] = user_csrf_token;
        }
        load_aside_request = $.ajax({
            type: 'POST',
            url: api_request_url,
            data: data,
            async: true,
            beforeSend: function() {
                if (load_aside_request != null) {
                    load_aside_request.abort();
                    load_aside_request = null;
                }
            },
            success: function(data) {}
        }).done(function(data) {
            if (isJSON(data)) {
              data = $.parseJSON(data);
              var content = data.content;
              
              if (data.content !== undefined) {
                totalitems = Object.keys(data.content).length;
                if (totalitems > 0) {
                  var targetUsers = [];
                  $.each(content, function(key, val) {
                    if ( val.user_id != userID ) {
                      const targetUser = {
                        userID: val.user_id,
                        userName: val.title
                      };
                      targetUsers.push(targetUser);
                    }
                  });
                  
                  zp.sendCallInvitation({
                      callees: targetUsers,
                      callType: ZegoUIKitPrebuilt.InvitationTypeVoiceCall,
                      timeout: 60, // Timeout duration (second). 60s by default, range from [1-600s].
                  }).then((res) => {
                      console.warn(res);
                  }).catch((err) => {
                      console.warn(err);
                  });
                }
              }
  
            }
        }).fail(function(qXHR, textStatus, errorThrown) {
            console.log("error");
        });
    } else if ($(".main .chatbox").attr('user_id') !== undefined) {
      const targetUserID = $(".main .chatbox").attr('user_id');
      const targetUserName = $(".main .chatbox .header .heading .title").text();
  
      const targetUser = {
          userID: targetUserID,
          userName: targetUserName
      };
      zp.sendCallInvitation({
          callees: [targetUser],
          callType: ZegoUIKitPrebuilt.InvitationTypeVoiceCall,
          timeout: 60, // Timeout duration (second). 60s by default, range from [1-600s].
      }).then((res) => {
          console.warn(res);
      }).catch((err) => {
          console.warn(err);
      });
    }
});

$("body").on('click', '.main .middle > .video_preview .close_player', function (e) {
    $('.main .middle > .video_preview').addClass('d-none');
    $('.main .middle > .video_preview > div').html('');

    if ($('.main .middle > .group_headers').hasClass('header_content_loaded')) {
        $('.main .middle > .group_headers').removeClass('d-none');
        $('.main .middle > .group_headers > .header_content').html(group_header_contents);
    }

});

$("body").on('click', '.main .middle > .iframe_window .close_iframe_window', function (e) {
    $('.main .middle > .iframe_window').addClass('d-none');
    $('.main .middle > .iframe_window > div').html('');

    if ($('.main .middle > .group_headers').hasClass('header_content_loaded')) {
        $('.main .middle > .group_headers').removeClass('d-none');
        $('.main .middle > .group_headers > .header_content').html(group_header_contents);
    }
});


$("body").on('click', '.main .middle > .group_headers .close_group_header', function (e) {
    group_header_contents = null;
    $('.main .middle > .group_headers > .header_content').html('');
    $('.main .middle > .group_headers').removeClass('header_content_loaded');
    $('.main .middle > .group_headers').addClass('d-none');
});



$("body").on('mouseenter', '.main .chatbox > .contents > .chat_messages > ul > li > div >.right > .header > .tools > .timestamp', function (e) {
    $('.main .chatbox > .contents > .date').hide();
    if ($(this).parents('.message').find('.date').attr('message_sent_on') !== undefined) {
        var message_sent_on = $(this).parents('.message').find('.date').attr('message_sent_on');
        $('.main .chatbox > .contents > .date > span').text(message_sent_on);
        $('.main .chatbox > .contents > .date').show();
    }
});

$("body").on('mouseleave', '.main .chatbox > .contents > .chat_messages > ul > li > div >.right > .header > .tools > .timestamp', function (e) {
    $('.main .chatbox > .contents > .date').hide();
});



$("body").on('click', '.preview_video', function (e) {

    $('.main .middle > .iframe_window').addClass('d-none');
    $('.main .middle > .iframe_window > div').html('');

    $('.main .middle > .group_headers').addClass('d-none');
    $('.main .middle > .group_headers > .header_content').html('');

    $('.main .middle > .video_preview').addClass('d-none');
    $('.main .middle > .video_preview > div').html('');

    var content = '';

    if ($(this).attr('video_file') !== undefined) {

        if ($(this).attr('mime_type') === undefined) {
            $(this).attr('mime_type', '');
        }

        if ($(this).attr('thumbnail') === undefined) {
            $(this).attr('thumbnail', '');
        }

        content += '<video id="video_preview" class="video-js vjs-theme-city" autoplay playsinline controls poster="' + $(this).attr('thumbnail') + '">';
        content += '<source src="' + $(this).attr('video_file') + '" type="' + $(this).attr('mime_type') + '" />';
        content += '</video>';
    } else if ($(this).attr('video_url') !== undefined) {
        content += '<video id="video_preview" class="video-js vjs-theme-city" autoplay playsinline controls poster="' + $(this).attr('thumbnail') + '">';
        content += '</video>';
    }

    if (content.length !== 0) {
        open_column('second');
        $('.main .middle > .video_preview > div').html(content);
        $('.main .middle > .video_preview').removeClass('d-none');

        if (video_preview !== null) {
            videojs('video_preview').dispose();
        }
        if ($(this).attr('video_url') === undefined) {
            video_preview = videojs('video_preview');
        } else {
            var video_provider = ($(this).attr('mime_type')).replace("video/", "");
            video_preview = videojs('video_preview', {
                controls: true,
                sources: [{
                    src: $(this).attr('video_url'), type: $(this).attr('mime_type')
                }],
                techOrder: [video_provider]
            });

        }
        video_preview.on('play', () => {

            if (audio_message_preview !== undefined && audio_message_preview !== null) {
                audio_message_preview.pause();
            }

            if (audio_player !== undefined && audio_player !== null) {
                audio_player.pause();
            }
        });
    }
});


function load_group_header(group_identifier) {
    if (group_identifier !== undefined) {

        var data = {
            load: 'group_header',
            group_id: group_identifier,
            skip_output: true
        };

        if (user_csrf_token !== null) {
            data["csrf_token"] = user_csrf_token;
        }

        load_group_header_request = $.ajax({
            type: 'POST',
            url: api_request_url,
            data: data,
            async: true,
            beforeSend: function () {
                if (load_group_header_request != null) {
                    load_group_header_request.abort();
                    load_group_header_request = null;
                }
            },
            success: function (data) { }
        }).done(function (data) {
            if ($.trim(data) !== '') {
                group_header_contents = data;
                $('.main .middle > .group_headers > .header_content').html(data);
                $('.main .middle > .group_headers').removeClass('d-none');
                $('.main .middle > .group_headers').addClass('header_content_loaded');
            }
        }).fail(function (qXHR, textStatus, errorThrown) {
            if (qXHR.statusText !== 'abort' && qXHR.statusText !== 'canceled') {
                console.log('ERROR : ' + errorThrown);
            }
        });
    }
}


$("body").on('click', '.iframe_embed', function (e) {

    if (video_preview !== undefined && video_preview !== null) {
        video_preview.pause();
    }

    if (audio_message_preview !== undefined && audio_message_preview !== null) {
        audio_message_preview.pause();
    }

    if (audio_player !== undefined && audio_player !== null) {
        audio_player.pause();
    }

    $('.main .middle > .video_preview').addClass('d-none');
    $('.main .middle > .video_preview > div').html('');

    $('.main .middle > .group_headers').addClass('d-none');

    $('.main .middle > .group_headers > .header_content').html('');

    $('.main .middle > .iframe_window > div').html('<iframe></iframe>');
    $('.main .middle > .iframe_window > div > iframe').attr('frameborder', 0);
    $('.main .middle > .iframe_window > div > iframe').attr('allowFullScreen', true);
    $('.main .middle > .iframe_window > div > iframe').attr('webkitallowfullscreen', true);
    $('.main .middle > .iframe_window > div > iframe').attr('mozallowfullscreen', true);
    $('.main .middle > .iframe_window > div > iframe').attr('src', $(this).attr('embed_url'));

    if ($(this).attr('iframe_class') !== undefined) {
        $('.main .middle > .iframe_window > div > iframe').addClass($(this).attr('iframe_class'));
    }

    $('.main .middle > .iframe_window').removeClass('d-none');
});