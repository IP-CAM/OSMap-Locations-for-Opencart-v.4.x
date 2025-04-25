<?php
/**
 * Module D.OSMap Locations
 *
 * @version 1.0
 * 
 * @author D.art <d.art.reply@gmail.com>
 * @copyright Copyright (C) Jan 2023 D.art <d.art.reply@gmail.com>.
 * @license	GNU General Public License version 3
 */

// Heading
$_['heading_title']        = 'OSMap Адреса';

// Tab
$_['tab_general']          = 'Общее';
$_['tab_locations']        = 'Список адресов';
$_['tab_add_location']     = 'Добавить адрес';
$_['tab_settings']         = 'Настройки';

// Entry
$_['entry_title']          = 'Заголовок';
$_['entry_attr_id']        = 'Атрибут ID';
$_['entry_general_name']   = 'Название';
$_['entry_general_title']  = 'Заголовок';
$_['entry_general_descr']  = 'Описание';
$_['entry_filter']         = 'Фильтр';
$_['entry_locations']      = 'Список адресов';
$_['entry_location_id']    = 'ID';
$_['entry_addition_info']  = 'Дополнительная информация';
$_['entry_action']         = 'Действие';
$_['entry_baloon_header']  = 'Шапка';
$_['entry_baloon_body']    = 'Описание';
$_['entry_baloon_footer']  = 'Подвал';
$_['entry_latitude']       = 'Широта (latitude)';
$_['entry_longitude']      = 'Долгота (longitude)';
$_['entry_coords']         = 'Координаты (lat,long)';
$_['entry_status']         = 'Статус';
$_['entry_key_api']        = 'API Ключ';
$_['entry_ver_api']        = 'API Версия';
$_['entry_limit_site']     = 'Адресов на страницу (Сайт)';
$_['entry_limit_admin']    = 'Адресов на страницу (Админ)';
$_['entry_map_settings']   = 'Настройки карты';
$_['entry_language']       = 'Язык';
$_['entry_language_code']  = 'Введите код языка';
$_['entry_clusterization'] = 'Кластеризация';
$_['entry_baloon_info']    = 'Информация об адресе (при клике)';
$_['entry_init_coords']    = 'Центрирование карты при загрузке страницы';
$_['entry_init_zoom']      = 'Масштабирование (Zoom) карты при загрузке страницы';
$_['entry_pan_zoom']       = 'Масштабирование (Zoom) карты при переходе к адресу';
$_['entry_json_coords']    = 'Обновление JSON-координат';
$_['entry_data_delete']    = 'Удалить все данные после деактивации модуля (глобальная настройка)';

// Text
$_['text_extension']       = 'Расширения';
$_['text_success']         = 'Настройки успешно изменены!';
$_['text_edit']            = 'Настройки модуля';
$_['text_loc_list_empty']  = 'Список адресов пуст.<br><button type="button" class="add_location-list">Добавьте новый адрес</button>';
$_['text_loc_list_fempty'] = 'Адреса не найдены.<br>Измените параметры фильтра.';
$_['text_update_not']      = 'Не обновлять';
$_['text_update_auto']     = 'Автоматически. При добавлении/обновлении/удалении адреса.';
$_['text_update_manual']   = 'Вручную. При нажатии кнопки "Обновить JSON-файл".';
$_['text_loc_place']       = 'Страна, Город, Улица, Дом, ...';
$_['text_filter_title']    = 'Введите заголовок';
$_['text_filter_header']   = 'Введите шапку';
$_['text_filter_footer']   = 'Введите подвал';
$_['text_filter_lat']      = 'Введите широту';
$_['text_filter_long']     = 'Введите долготу';

// Help
$_['help_btn_add']         = 'Добавить';
$_['help_btn_rem_check']   = 'Удалить выбранные адреса';
$_['help_key_api']         = 'Ключ для «JavaScript API и HTTP Геокодер»';
$_['help_language_code']   = 'Введите код языка в формате: language_REGION. Например, для английского языка: en_US';

// Button
$_['button_add']           = 'Добавить';
$_['button_add_location']  = 'Добавить адрес';
$_['button_remove_check']  = 'Удалить выбранные адреса';
$_['button_lock']          = 'Заблокировать';
$_['button_unlock']        = 'Разблокировать';
$_['button_filter']        = 'Фильтр';
$_['button_filter_reset']  = 'Сбросить';
$_['button_update_json']   = 'Обновить JSON-файл';
$_['button_save_sets']     = 'Сохранить настройки';

// Alert
$_['alert_confirm_rem']    = 'Подтвердите удаление адреса';
$_['alert_confirm_remove'] = 'Подтвердите удаление выбранных адресов';

// Success
$_['success_json_put']     = 'JSON-файл успешно обновлен!';
$_['success_mes_add']      = 'Адрес успешно добавлен! Чтобы увидеть адрес в списке, <a href="#" onclick="location.reload(); return false;" style="font-weight: 700; text-decoration: underline;">перезагрузите страницу</a>.';
$_['success_mes_open']     = 'Адрес успешно получен!';
$_['success_mes_save']     = 'Адрес успешно обновлен!';
$_['success_mes_rem']      = 'Адрес успешно удален!';
$_['success_mes_remove']   = 'Адреса успешно удалены!';

// Error
$_['error_permission']     = 'У Вас нет прав для изменения модуля!';
$_['error_required']       = 'Внимательно проверьте модуль на ошибки!';
$_['error_key_api']        = 'Ключ API должен быть от 3 символов!';
$_['error_json_put']       = 'JSON-файл не удалось обновить! Проверьте настройки!';
$_['error_name']           = 'Название модуля должно быть от 3 до 64 символов!';
$_['error_mes_add']        = 'Адрес не добавлен! Проверьте форму!';
$_['error_mes_open']       = 'Адрес не удалось получить! Обновите страницу и попробуйте еще раз!';
$_['error_mes_save']       = 'Адрес не удалось обновить! Проверьте форму!';
$_['error_mes_rem']        = 'Адрес не удален! Проверьте форму!';
$_['error_mes_remove']     = 'Адреса не удалены! Проверьте форму!';
$_['error_title']          = 'Заголовок должен быть от 3 до 90 символов!';
$_['error_latitude']       = 'Широта некорректна!';
$_['error_longitude']      = 'Долгота некорректна!';
$_['error_coords']         = 'Координаты должны быть от 10 до 30 символов!';
$_['error_module_id']      = 'Отсутствует ID модуля!';