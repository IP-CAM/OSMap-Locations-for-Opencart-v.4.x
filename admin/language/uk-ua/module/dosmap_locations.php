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
$_['heading_title']        = 'OSMap Адреси';

// Tab
$_['tab_general']          = 'Загальне';
$_['tab_locations']        = 'Перелік адрес';
$_['tab_add_location']     = 'Додати адресу';
$_['tab_settings']         = 'Налаштування';

// Entry
$_['entry_title']          = 'Заголовок';
$_['entry_attr_id']        = 'Атрибут ID';
$_['entry_general_name']   = 'Назва';
$_['entry_general_title']  = 'Заголовок';
$_['entry_general_descr']  = 'Опис';
$_['entry_filter']         = 'Фільтр';
$_['entry_locations']      = 'Перелік адрес';
$_['entry_location_id']    = 'ID';
$_['entry_addition_info']  = 'Додаткова інформація';
$_['entry_action']         = 'Дія';
$_['entry_baloon_header']  = 'Шапка';
$_['entry_baloon_body']    = 'Опис';
$_['entry_baloon_footer']  = 'Підвал';
$_['entry_latitude']       = 'Широта (latitude)';
$_['entry_longitude']      = 'Довгота (longitude)';
$_['entry_coords']         = 'Координати (lat,long)';
$_['entry_status']         = 'Статус';
$_['entry_key_api']        = 'API Ключ';
$_['entry_ver_api']        = 'API Версія';
$_['entry_limit_site']     = 'Адрес на сторінку (Сайт)';
$_['entry_limit_admin']    = 'Адрес на сторінку (Адмін)';
$_['entry_map_settings']   = 'Налаштування мапи';
$_['entry_language']       = 'Мова';
$_['entry_language_code']  = 'Введіть код мови';
$_['entry_clusterization'] = 'Кластеризація';
$_['entry_baloon_info']    = 'Інформація про адресу (при натисканні)';
$_['entry_init_coords']    = 'Центрування мапи під час завантаження сторінки';
$_['entry_init_zoom']      = 'Масштабування (Zoom) мапи під час завантаження сторінки';
$_['entry_pan_zoom']       = 'Масштабування (Zoom) мапи під час переходу до адреси';
$_['entry_json_coords']    = 'Оновлення JSON-координат';
$_['entry_data_delete']    = 'Видалити всі дані після деактивації модуля (глобальне налаштування)';

// Text
$_['text_extension']       = 'Розширення';
$_['text_success']         = 'Налаштування успішно змінено!';
$_['text_edit']            = 'Налаштування модуля';
$_['text_loc_list_empty']  = 'Перелік адрес порожній.<br><button type="button" class="add_location-list">Додати нову адресу</button>';
$_['text_loc_list_fempty'] = 'Адреси не знайдено.<br>Змініть параметри фільтра.';
$_['text_update_not']      = 'Не оновлювати';
$_['text_update_auto']     = 'Автоматично. При додаванні/оновленні/видаленні адреси.';
$_['text_update_manual']   = 'Вручну. Якщо натиснути кнопку "Оновити JSON-файл".';
$_['text_loc_place']       = 'Країна, Місто, Вулиця, Будинок, ...';
$_['text_filter_title']    = 'Введіть заголовок';
$_['text_filter_header']   = 'Введіть шапку';
$_['text_filter_footer']   = 'Введіть підвал';
$_['text_filter_lat']      = 'Введіть широту';
$_['text_filter_long']     = 'Введіть довготу';

// Help
$_['help_btn_add']         = 'Додати';
$_['help_btn_rem_check']   = 'Видалити обрані адреси';
$_['help_key_api']         = 'Ключ для «JavaScript API и HTTP Геокодер»';
$_['help_language_code']   = 'Введіть код мови в форматі: language_REGION. Наприклад, для англійської мови: en_US';

// Button
$_['button_add']           = 'Додати';
$_['button_add_location']  = 'Додати адресу';
$_['button_remove_check']  = 'Видалити обрані адреси';
$_['button_lock']          = 'Заблокувати';
$_['button_unlock']        = 'Розблокувати';
$_['button_filter']        = 'Фільтр';
$_['button_filter_reset']  = 'Скинути';
$_['button_update_json']   = 'Оновити JSON-файл';
$_['button_save_sets']     = 'Зберегти налаштування';

// Alert
$_['alert_confirm_rem']    = 'Підтвердьте видалення адреси';
$_['alert_confirm_remove'] = 'Підтвердьте видалення вибраних адрес';

// Success
$_['success_json_put']     = 'JSON-файл успішно оновлено!';
$_['success_mes_add']      = 'Адреса успішно додана! Щоб побачити адресу у списку, <a href="#" onclick="location.reload(); return false;" style="font-weight: 700; text-decoration: underline;">перезавантажте сторінку</a>.';
$_['success_mes_open']     = 'Адреса успішно отримана!';
$_['success_mes_save']     = 'Адреса успішно оновлена!';
$_['success_mes_rem']      = 'Адреса успішно видалена!';
$_['success_mes_remove']   = 'Адреси успішно видалені!';

// Error
$_['error_permission']     = 'У Вас немає прав для зміни модуля!';
$_['error_required']       = 'Уважно перевірте модуль на помилки!';
$_['error_key_api']        = 'Ключ API має бути від 3 символів!';
$_['error_json_put']       = 'JSON файл не вдалося оновити! Перевірте налаштування!';
$_['error_name']           = 'Назва модуля має бути від 3 до 64 символів!';
$_['error_mes_add']        = 'Адреса не додана! Перевірте форму!';
$_['error_mes_open']       = 'Адресу не вдалося отримати! Оновіть сторінку та спробуйте ще раз!';
$_['error_mes_save']       = 'Адресу не вдалося оновити! Перевірте форму!';
$_['error_mes_rem']        = 'Адреса не видалена! Перевірте форму!';
$_['error_mes_remove']     = 'Адреси не видалено! Перевірте форму!';
$_['error_title']          = 'Заголовок має бути від 3 до 90 символів!';
$_['error_latitude']       = 'Широта некоректна!';
$_['error_longitude']      = 'Довгота некоректна!';
$_['error_coords']         = 'Координати мають бути від 10 до 30 символів!';
$_['error_module_id']      = 'Немає ID модуля!';