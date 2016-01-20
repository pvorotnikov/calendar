'use strict';

$.material.init()

$(document).ready(function() {

    // perform login
    $('#loginForm').on('submit', function($e) {
        $e.preventDefault();

        var username = this.username.value;
        var password = this.password.value;
        var $form = $(this);

        if ('' === username || '' === password) {
            return;
        }

        $.ajax({
            type: 'POST',
            url: $form.attr('action'),
            data: {username: username, password: password},
            dataType: 'json',
            cache: false,
            beforeSend: function() {
                $('.alert', $form).remove();
            },
            success: function(data) {
                if ('ok' === data.status) {
                    $form.append('<div class="alert alert-success" role="alert">Success</div>');

                    // reload page
                    setTimeout(function() {
                        window.location = '/';
                    }, 1000);
                } else {
                    $form.append('<div class="alert alert-warning" role="alert">' + data.errorMessage +'</div>');
                }
            },
            error: function(xhr, status, err) {
                console.error(err.toString());
                $form.append('<div class="alert alert-danger" role="alert">Error</div>');
            }
        });

    });

    // perform signup
    $('#signupForm').on('submit', function($e) {
        $e.preventDefault();

        var username = this.username.value;
        var password = this.password.value;
        var passwordrepeat = this.passwordrepeat.value;
        var email = this.email.value;
        var $form = $(this);

        if ('' === username || '' === password || '' === passwordrepeat || '' === email) {
            return;
        }

        $.ajax({
            type: 'POST',
            url: $form.attr('action'),
            data: {username: username, password: password, passwordrepeat: passwordrepeat, email: email},
            dataType: 'json',
            cache: false,
            beforeSend: function() {
                $('.alert', $form).remove();
            },
            success: function(data) {
                if ('ok' === data.status) {
                    $form.append('<div class="alert alert-success" role="alert">Success</div>');

                    // reload page
                    setTimeout(function() {
                        window.location = '/';
                    }, 1000);
                } else {
                    $form.append('<div class="alert alert-warning" role="alert">' + data.errorMessage +'</div>');
                }
            },
            error: function(xhr, status, err) {
                console.error(err.toString());
                $form.append('<div class="alert alert-danger" role="alert">Error</div>');
            }
        });

    });

    // perform settings update
    $('#settingsForm').on('submit', function($e) {
        $e.preventDefault();

        var password = this.password.value;
        var passwordrepeat = this.passwordrepeat.value;
        var email = this.email.value;
        var $form = $(this);

        $.ajax({
            type: 'POST',
            url: $form.attr('action'),
            data: {password: password, passwordrepeat: passwordrepeat, email: email},
            dataType: 'json',
            cache: false,
            beforeSend: function() {
                $('.alert', $form).remove();
            },
            success: function(data) {
                if ('ok' === data.status) {
                    $form.append('<div class="alert alert-success" role="alert">Success</div>');

                    // reload page
                    setTimeout(function() {
                        window.location = '/?page=settings';
                    }, 1000);
                } else {
                    $form.append('<div class="alert alert-warning" role="alert">' + data.errorMessage +'</div>');
                }
            },
            error: function(xhr, status, err) {
                console.error(err.toString());
                $form.append('<div class="alert alert-danger" role="alert">Error</div>');
            }
        });

    });

    // perform logout
    $('#logout').on('click', function($e) {
        $e.preventDefault();
        var $anchor = $(this);

        $.ajax({
            type: 'GET',
            url: $anchor.attr('href'),
            dataType: 'json',
            cache: false,
            success: function(data) {
                if ('ok' === data.status) {
                    window.location = '/';
                } else {
                    console.error('Error', data);
                }
            },
            error: function(xhr, status, err) {
                console.error(err.toString());
            }
        });
    });

    // setup date-time pickers
    $('.input-group.date').datetimepicker();

    // open/close events form
    $('#addEvent').on('click', function($e) {
        $e.preventDefault();

        var $anchor = $($e.target);
        var $panel = $($anchor.attr('href'));
        if ($panel.hasClass('hidden')) {
            $panel.removeClass('hidden');
            $('.glyphicon', $anchor).removeClass('glyphicon-plus-sign').addClass('glyphicon-minus-sign');
        } else {
            $panel.addClass('hidden');
            $('.glyphicon', $anchor).removeClass('glyphicon-minus-sign').addClass('glyphicon-plus-sign');
        }
    });

    // perform event add
    $('#eventForm, #eventEditForm').on('submit', function($e) {
        $e.preventDefault();

        var startTime = new Date(this.startTime.value).getTime();
        var endTime = '' !== this.endTime.value ? new Date(this.endTime.value).getTime() : '';
        var eventName = this.eventName.value;
        var eventDescription = this.eventDescription.value;
        var eventId = this.eventId ? this.eventId.value : 0;
        var $form = $(this);

        $.ajax({
            type: 'POST',
            url: $form.attr('action'),
            data: {id: eventId, startTime: startTime, endTime: endTime, eventName: eventName, eventDescription: eventDescription},
            dataType: 'json',
            cache: false,
            beforeSend: function() {
                $('.alert', $form).remove();
            },
            success: function(data) {
                if ('ok' === data.status) {
                    $form.append('<div class="alert alert-success" role="alert">Success</div>');

                    // close add event form
                    setTimeout(function() {
                        $('#addEvent').click();
                        calendar.view();
                    }, 1000);

                } else {
                    $form.append('<div class="alert alert-warning" role="alert">' + data.errorMessage +'</div>');
                }
            },
            error: function(xhr, status, err) {
                console.error(err.toString());
                $form.append('<div class="alert alert-danger" role="alert">Error</div>');
            }
        });

    });

    // perform event delete
    $('#eventDelete').on('click', function($e) {

        $e.preventDefault();

        var result = confirm("You are about to delete this event. Do you want to continue?");

        if (!result) {
            return;
        }

        var eventId = $($e.target).attr('data-eventid');

        $.ajax({
            type: 'POST',
            url: '/ajax/calendar.php?do=delete',
            data: {id: eventId},
            dataType: 'json',
            cache: false,
            success: function(data) {
                if ('ok' === data.status) {
                    window.location = '/?page=calendar';
                } else {
                    console.error(data.errorMessage);
                }
            },
            error: function(xhr, status, err) {
                console.error(err.toString());
            }
        });

    });

    $('#calendarControl .dropdown-menu a').on('click', function($e) {
        $e.preventDefault();
        var $anchor = $(this);
        var option = $anchor.attr('data-action');
        $('#calendarControl button').html(option + ' <span class="caret"></span>');
        calendar.setOptions({ view: option.toLowerCase() });
        calendar.view();
        $('#calendarHeading').text(calendar.getTitle());
    });

    $('#calendarControl a.btn').on('click', function($e) {
        $e.preventDefault();
        var $anchor = $(this);
        var option = $anchor.attr('data-action');
        calendar.navigate(option);
        $('#calendarHeading').text(calendar.getTitle());
    });

    var calendar = $("#calendar").calendar({
        tmpl_path: "/public/lib/bootstrap-calendar/tmpls/",
        events_source: 'ajax/calendar.php?do=events',
        modal: "#events-modal",
        modal_type: "template",
        modal_title: function(e) {
            return e.title
        }
    });

    $('#calendarHeading').text(calendar.getTitle());

});
