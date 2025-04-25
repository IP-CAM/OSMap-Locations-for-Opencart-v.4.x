/**
 * Script Module D.OSMap Locations
 *
 * @version 1.0
 * 
 * @author D.art <d.art.reply@gmail.com>
 */

// Fire on document
jQuery(function($) {
    // Alert Timeout.
    var alertTimeout = 0;

    // Fire on document.
    $(document).ready(function() {
        // Open '#tab-locations'.
        const urlParams = new Proxy(new URLSearchParams(window.location.search), {
            get: (searchParams, prop) => searchParams.get(prop),
        });
        var tabParam = urlParams.tab;
        if (tabParam && tabParam == 'locations') {
            $('#form-module .nav-tabs a[href="#tab-locations"]').trigger('click');
        }

        // Set color to status of locations on load page.
        $('#tab-locations table.locations-list tbody .field-status').each(function(index, item){
            colorToStatus(item);
        });

        // Set color to status of locations when status select.
        $('#tab-locations table.locations-list tbody .field-status').on('change', function(){
            colorToStatus(this);
        });

        // Change language from Settings.
        $('#input-dosmap_locs_map_language').on('change', function(){
            var _this = $(this);

            if (_this.val() == '--') {
                _this.closest('.map_settings-inner').find('.map_settings-custom').show();
            } else {
                _this.closest('.map_settings-inner').find('.map_settings-custom').hide();
            }
        });

        // Filter Locations.
        $('#button-filter').on('click', function() {
            var url = '';

            var filter_title = $('input[name=\'filter[filter_title]\']').val();
            if (filter_title) {
                url += '&filter_title=' + encodeURIComponent(filter_title);
            }

            var filter_baloon_header = $('input[name=\'filter[filter_baloon_header]\']').val();
            if (filter_baloon_header) {
                url += '&filter_baloon_header=' + encodeURIComponent(filter_baloon_header);
            }

            var filter_baloon_footer = $('input[name=\'filter[filter_baloon_footer]\']').val();
            if (filter_baloon_footer) {
                url += '&filter_baloon_footer=' + encodeURIComponent(filter_baloon_footer);
            }

            var filter_latitude = $('input[name=\'filter[filter_latitude]\']').val();
            if (filter_latitude) {
                url += '&filter_latitude=' + encodeURIComponent(filter_latitude);
            }

            var filter_longitude = $('input[name=\'filter[filter_longitude]\']').val();
            if (filter_longitude) {
                url += '&filter_longitude=' + encodeURIComponent(filter_longitude);
            }

            var filter_status = $('select[name=\'filter[filter_status]\']').val();
            if (filter_status !== '') {
                url += '&filter_status=' + encodeURIComponent(filter_status);
            }

            location = filterURL.replaceAll('&amp;','&') + url;
            //window.history.pushState({}, null, filterURL.replaceAll('&amp;','&') + url);
            //$('#locations-table').load(filterURL.replaceAll('&amp;','&') + url);
        });

        // Reset Filter Locations.
        $('#button-filter-reset').on('click', function() {
            location = filterResetURL.replaceAll('&amp;','&');
        });

        // Filter autocomplete Locations.
        // Filter Title.
        $('input[name=\'filter[filter_title]\']').autocomplete({
            'source': function(request, response) {
                $.ajax({
                    url: filterAutocomplete.replaceAll('&amp;','&') + '&filter_title=' +  encodeURIComponent(request),
                    dataType: 'json',
                    success: function(json) {
                        response($.map(json, function(item) {
                            return {
                                label: item['title'],
                                value: item['location_id']
                            }
                        }));
                    }
                });
            },
            'select': function(item) {
                $('input[name=\'filter[filter_title]\']').val(item['label']);
            }
        });

        // Filter autocomplete Locations.
        // Filter Balloon Header.
        $('input[name=\'filter[filter_baloon_header]\']').autocomplete({
            'source': function(request, response) {
                $.ajax({
                    url: filterAutocomplete.replaceAll('&amp;','&') + '&filter_baloon_header=' +  encodeURIComponent(request),
                    dataType: 'json',
                    success: function(json) {
                        response($.map(json, function(item) {
                            return {
                                label: item['baloon_header'],
                                value: item['location_id']
                            }
                        }));
                    }
                });
            },
            'select': function(item) {
                $('input[name=\'filter[filter_baloon_header]\']').val(item['label']);
            }
        });

        // Filter autocomplete Locations.
        // Filter Balloon Footer.
        $('input[name=\'filter[filter_baloon_footer]\']').autocomplete({
            'source': function(request, response) {
                $.ajax({
                    url: filterAutocomplete.replaceAll('&amp;','&') + '&filter_baloon_footer=' +  encodeURIComponent(request),
                    dataType: 'json',
                    success: function(json) {
                        response($.map(json, function(item) {
                            return {
                                label: item['baloon_footer'],
                                value: item['location_id']
                            }
                        }));
                    }
                });
            },
            'select': function(item) {
                $('input[name=\'filter[filter_baloon_footer]\']').val(item['label']);
            }
        });

        // Add Location.
        $('#tab-add_location .button-add_location').on('click', function() {
            var button = $(this);
            var fields = $('#tab-add_location .field-section-add_location');

            $.ajax({
                url: action_add.replaceAll('&amp;','&'),
                type: 'post',
                data: fields.serialize(),
                dataType: 'json',
                beforeSend: function() {
                    // Reset Alert Timeout.
                    alertTimeout = 0;

                    button.button('loading');

                    //$('#alert-modal').html('');
                    $('#tab-add_location .text-danger').remove();
                },
                complete: function() {
                    button.button('reset');
                },
                success: function(json) {
                    //console.log(json);

                    messageAlertModal(json['success'], json['message']);

                    if (json['json_file']) {
                        messageAlertModal(json['json_file']['success'], json['json_file']['message']);
                    }

                    if (json['errors']['module_id']) {
                        messageAlertModal(json['success'], json['errors']['module_id']);
                    }

                    if (json['success']) {
                        fields.each(function(index, item){
                            var _item = $(item);

                            if (!_item.is('.field-hidden')) _item.val('');
                            if (_item.hasClass('field-status')) _item.val('0');
                            if (_item.hasClass('field-baloon_body')) _item.summernote('reset');
                        });
                    } else {
                        if (json['errors']) {
                            for (key in json['errors']) {
                                if (key == 'description') {
                                    for (language_id in json['errors']['description']) {
                                        for (error in json['errors']['description'][language_id]) {
                                            $('#tab-add_location #language_add_location_' + language_id + ' .field-' + error).after('<div class="text-danger">' + json['errors']['description'][language_id][error] + '</div>');
                                        }
                                    }
                                } else {
                                    if (key != 'module_id') {
                                        $('#tab-add_location #input-add_location-' + key).after('<div class="text-danger">' + json['errors'][key] + '</div>');
                                    }
                                }
                            }
                        }
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        });

        // Open Location.
        $('#locations-list tbody button.edit').on('click', function() {
            var button = $(this);
            var tr = button.closest('tr');

            if (tr.is('.editing')) {
                // Disable Summernote for current Location.
                toggleCKEditorTextarea(tr, false);

                // View Location.
                toggleLocation(true, button);
            } else {
                // Collapse Location.
                toggleLocation(false, button);

                if (tr.is('.updated')) {
                    // Enable Summernote for current Location.
                    toggleCKEditorTextarea(tr, true);

                    // View Location.
                    toggleLocation(true, button);
                } else {
                    var fields = tr.find('.field-section-locations');

                    // Get Location Descriptions.
                    openLocationAJAX(button, button, tr, fields);
                }
            }
        });

        // Edit Location.
        $('#locations-list tbody button.save').on('click', function() {
            var button = $(this);
            var fields = button.closest('tr').find('.field-section-locations');

            // Save Location to DB.
            editLocationAJAX(button, fields);
        });

        // Edit Status of Location.
        $('#locations-list tbody .field-status').on('change', function() {
            var select = $(this);
            var tr = select.closest('tr');

            if (!tr.is('.editing')) {
                var button = tr.find('button.save');
                var fields = tr.find('.field-section-locations');

                // Get Location Descriptions.
                // Save Location to DB.
                openLocationAJAX(select, button, tr, fields, true, false);
            }
        });

        // Delete Location.
        $('#locations-list tbody button.remove').on('click', function() {
            var removeOrNotRemove = confirm(alert_confirm_rem);

            if (removeOrNotRemove || (removeOrNotRemove === null)) {
                var button = $(this);
                var fields = button.closest('tr').find('.field-section-locations');

                $.ajax({
                    url: action_delete.replaceAll('&amp;','&'),
                    type: 'post',
                    data: fields.serialize(),
                    dataType: 'json',
                    beforeSend: function() {
                        // Reset Alert Timeout.
                        alertTimeout = 0;

                        //$('#alert-modal').html('');
                        $('#locations-list .text-danger').remove();

                        // Add Loader.
                        addLoaderDOSMap(button.closest('tr').find('.addition_info'));
                    },
                    complete: function() {
                        // Remove Loader.
                        removeLoaderDOSMap(button.closest('tr').find('.addition_info'));
                    },
                    success: function(json) {
                        //console.log(json);

                        messageAlertModal(json['success'], json['message']);

                        if (json['json_file']) {
                            messageAlertModal(json['json_file']['success'], json['json_file']['message']);
                        }

                        if (json['errors']['module_id']) {
                            messageAlertModal(json['success'], json['errors']['module_id']);
                        }

                        if (json['success']) {
                            button.closest('tr').remove();
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                });
            }
        });

        // Open form 'Add Location'.
        $('#tab-locations').on('click', 'button.add_location-list', function() {
            $('#tabs-main a').removeClass('active');
            $('#tabs-main .nav-add_location a').addClass('active');

            $('#tab-content-main > .tab-pane').removeClass('active');
            $('#tab-add_location').addClass('active');
        });

        // Delete List checked Locations.
        $('#tab-locations button.remove_locations-list').on('click', function() {
            var removeOrNotRemove = confirm(alert_confirm_remove);

            if (removeOrNotRemove || (removeOrNotRemove === null)) {
                var button = $(this);
                var fields = $('#locations-list input[name*=\'location_selected\']:checked');

                if (fields.length) {
                    $.ajax({
                        url: action_delete.replaceAll('&amp;','&'),
                        type: 'post',
                        data: fields.serialize(),
                        dataType: 'json',
                        beforeSend: function() {
                            // Reset Alert Timeout.
                            alertTimeout = 0;

                            button.button('loading');

                            //$('#alert-modal').html('');
                            $('#locations-list .text-danger').remove();

                            // Add Loader.
                            $('#tab-locations .panel-body-inner-locations').addClass('loader');
                            addLoaderDOSMap($('#tab-locations .panel-body-inner-locations'));
                        },
                        complete: function() {
                            button.button('reset');

                            // Remove Loader.
                            $('#tab-locations .panel-body-inner-locations').removeClass('loader');
                            removeLoaderDOSMap($('#tab-locations .panel-body-inner-locations'));
                        },
                        success: function(json) {
                            //console.log(json);

                            messageAlertModal(json['success'], json['message']);

                            if (json['json_file']) {
                                messageAlertModal(json['json_file']['success'], json['json_file']['message']);
                            }

                            if (json['errors']['module_id']) {
                                messageAlertModal(json['success'], json['errors']['module_id']);
                            }

                            if (json['success']) {
                                fields.each(function(index, item){
                                    $(item).closest('tr').remove();
                                });
                            }
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                        }
                    });
                } else {
                    // Reset Alert Timeout.
                    alertTimeout = 0;

                    //$('#alert-modal').html('');

                    messageAlertModal(false, error_mes_remove);
                }
            }
        });

        // Update JSON-file.
        $('#button-update_json').on('click', function() {
            var button = $(this);

            $.ajax({
                url: action_update_json.replaceAll('&amp;', '&'),
                type: 'post',
                data: '',
                dataType: 'json',
                beforeSend: function() {
                    // Reset Alert Timeout.
                    alertTimeout = 0;

                    button.button('loading');

                    //$('#alert-modal').html('');
                },
                complete: function() {
                    button.button('reset');
                },
                success: function(json) {
                    //console.log(json);

                    if (json['json_file']) {
                        messageAlertModal(json['json_file']['success'], json['json_file']['message']);
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        });

        // Open Location. AJAX.
        function openLocationAJAX(item, button, tr, fields, editLoc = false, viewLoc = true) {
            $.ajax({
                url: action_open.replaceAll('&amp;','&'),
                type: 'post',
                data: fields.serialize(),
                dataType: 'json',
                beforeSend: function() {
                    // Reset Alert Timeout.
                    alertTimeout = 0;

                    //$('#alert-modal').html('');

                    // Add Loader.
                    addLoaderDOSMap(tr.find('.addition_info'));
                },
                complete: function() {
                    // Remove Loader.
                    removeLoaderDOSMap(tr.find('.addition_info'));
                },
                success: function(json) {
                    //console.log(json);

                    if (json['success']) {
                        if (json['location']) {
                            for (location_id in json['location']) {
                                for (language_id in json['location'][location_id]) {
                                    for (key in json['location'][location_id][language_id]) {
                                        let div = $('<div></div>');
                                            div.html(json['location'][location_id][language_id][key]);
                                        let html = div.text();

                                        tr.find('input#input-location-' + location_id + '-' + language_id + '-' + key).val(html);
                                        tr.find('select#input-location-' + location_id + '-' + language_id + '-' + key).val(html);
                                        tr.find('textarea#input-location-' + location_id + '-' + language_id + '-' + key).text(html);
                                    }
                                }
                            }

                            // Enable Summernote for current Location.
                            toggleCKEditorTextarea(tr, true);

                            tr.addClass('updated');
                        }

                        // Edit Location.
                        if (editLoc) editLocationAJAX(button, fields);

                        // View Location.
                        if (viewLoc) toggleLocation(true, button);
                    } else {
                        messageAlertModal(json['success'], json['message']);

                        if (json['errors']) {
                            if (json['errors']['location']) {
                                for (location_id in json['errors']['location']) {
                                    for (error in json['errors']['location'][location_id]) {
                                        if (error == 'location_id') {
                                            messageAlertModal(json['success'], json['errors']['location'][location_id][error]);
                                        }
                                    }
                                }
                            }
                        }

                        if (json['errors']['module_id']) {
                            messageAlertModal(json['success'], json['errors']['module_id']);
                        } else {
                            // View Location.
                            if (!tr.is('.editing')) {
                                toggleLocation(true, button);
                            }
                        }
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        }

        // Edit Location. AJAX.
        function editLocationAJAX(button, fields) {
            $.ajax({
                url: action_edit.replaceAll('&amp;','&'),
                type: 'post',
                data: fields.serialize(),
                dataType: 'json',
                beforeSend: function() {
                    // Reset Alert Timeout.
                    alertTimeout = 0;

                    //$('#alert-modal').html('');
                    $('#locations-list .text-danger').remove();

                    // Add Loader.
                    addLoaderDOSMap(button.closest('tr').find('.addition_info'));
                },
                complete: function() {
                    // Remove Loader.
                    removeLoaderDOSMap(button.closest('tr').find('.addition_info'));
                },
                success: function(json) {
                    //console.log(json);

                    messageAlertModal(json['success'], json['message']);

                    if (json['json_file']) {
                        messageAlertModal(json['json_file']['success'], json['json_file']['message']);
                    }

                    if (json['errors']['module_id']) {
                        messageAlertModal(json['success'], json['errors']['module_id']);
                    }

                    if (json['success']) {
                        var titleValue = button.closest('tr').find('.field-title-' + json['language_id']).val();
                        button.closest('tr').find('.location_title').html(titleValue);

                        $('#locations-list tbody button.edit.active').trigger('click');
                    } else {
                        if (json['errors']) {
                            if (json['errors']['location']) {
                                for (location_id in json['errors']['location']) {
                                    for (error in json['errors']['location'][location_id]) {
                                        if (error == 'description') {
                                            for (language_id in json['errors']['location'][location_id][error]) {
                                                for (key in json['errors']['location'][location_id][error][language_id]) {
                                                    $('#locations-list #input-location-' + location_id + '-' + language_id + '-' + key).after('<div class="text-danger">' + json['errors']['location'][location_id][error][language_id][key] + '</div>');
                                                }
                                            }
                                        } else if (error == 'location_id') {
                                            messageAlertModal(json['success'], json['errors']['location'][location_id][error]);
                                        } else {
                                            $('#locations-list #input-location-' + location_id + '-' + error).after('<div class="text-danger">' + json['errors']['location'][location_id][error] + '</div>');
                                        }
                                    }
                                }
                            }
                        }

                        // View Location.
                        if (!button.closest('tr').is('.editing')) {
                            toggleLocation(false, button);
                            toggleLocation(true, button);
                        }
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        }
    });

    // Set color to Status.
    function colorToStatus(item) {
        if ($(item).val() > 0) {
            $(item).css('color', '#0b970b');
        } else {
            $(item).css('color', '#ad0404');
        }
    }

    // Message Alert.
    function messageAlertModal(success, message) {
        /*
        setTimeout(function() {
            html  = '';
            html += '<div class="alert ' + (success ? 'alert-success' : 'alert-danger') + ' alert-dismissible" style="margin: 0 0 10px;">';
                html += '<i class="fas fa-exclamation-circle"></i> <span>' + message + '</span>';
                html += '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="box-shadow: none;"></button>';
            html += '</div>';

            var messageHTML = $(html).hide().fadeIn(1000);

            $('#alert-modal').append(messageHTML);

            setTimeout(function() {
                messageHTML.hide(400);

                setTimeout(function(){ messageHTML.remove(); }, 500);
            }, 10000);
        }, 100 + alertTimeout);

        // Increase Alert Timeout.
        alertTimeout += 1000;
        */

        html  = '';
        html += '<div class="alert ' + (success ? 'alert-success' : 'alert-danger') + ' alert-dismissible" style="margin: 0 0 10px;">';
            html += '<i class="fas fa-exclamation-circle"></i> <span>' + message + '</span>';
            html += '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="box-shadow: none;"></button>';
        html += '</div>';

        $('#alert-modal').append(html);
    }

    // View/Collapse Location.
    function toggleLocation(status, button = {}) {
        if (status) {
            button.toggleClass('active');
            button.closest('tr').toggleClass('editing');

            button.closest('tr').find('.location_info').toggle(200, 'linear');
        } else {
            $('#locations-list tbody button.edit').removeClass('active');
            $('#locations-list tbody tr').removeClass('editing');

            $('#locations-list .location_info').hide(200, 'linear');
        }
    }

    // Enable Summernote Editor for textarea.
    function toggleCKEditorTextarea(tr, status) {
        if (status) {
            //var widthTD = tr.find('td.addition_info').width() - 20;

            tr.find('.field-baloon_body').ckeditor();
        } else {
            // tr.find('.field-baloon_body').ckeditor().destroy();
        }
    }

    // Add Loader.
    function addLoaderDOSMap(container) {
        container.append('<div class="loader-container"><div class="lds-dual-ring"></div></div>');
    }

    // Remove Loader.
    function removeLoaderDOSMap(container) {
        container.find('.loader-container').remove();
    }
});