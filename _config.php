<?php
class RxpFlexTheme
{
    public static $variables = [];
    private static $colorScheme;
    private static $config;

    public static function getConfig()
    {
        if (self::$config) {
            return self::$config;
        }

        $layout_info = Context::get('layout_info');
        self::$config = [
            'color_scheme' => $layout_info->color_scheme ?? 'auto',
            'container_light' => $layout_info->container_light ?? 'Y',
            'primary_color' => $layout_info->primary_color ?? '#9349ff',
            'font_family' => $layout_info->font_family ?? 'ari',
            'container_width' => $layout_info->container_width ?? '1032px',
            'container_padding_tight' => $layout_info->container_padding_tight ?? 'N',
            'social_login' => $layout_info->social_login ?? 'N',
            'social_login_service' => $layout_info->social_login_service ?? [],
            'footer_copyright' => $layout_info->footer_copyright ?? 'Designed by CALVINSNAX.',
            'logo_img' => $layout_info->logo_img ?? null,
            'logo_img_dark' => $layout_info->logo_img_dark ?? null,
            'logo_text' => $layout_info->logo_text ?? 'RXP FLEX',
            'logo_url' => $layout_info->logo_url ?? null,
            'header_container_expand' => $layout_info->header_container_expand === 'N' ?: 'Y',
            'header_search' => $layout_info->header_search ?? 'Y',
            'board_thumbnail' => $layout_info->board_thumbnail ?? 'N'
        ];

        return self::$config;
    }

    // 변수 시스템
    public static function init()
    {
        $config = self::getConfig();

        $base = array(
            'base-container' => '1032px',
            'base-padding' => '2rem',
            'base-padding-m' => '1.25rem',
        );

        $font = array(
            'font-family' => "'Arita-dotum-Medium', 'Noto Sans KR', 'Noto Sans Korean', -apple-system, 'SF Pro Display', 'Helvetica Neue',  'Apple SD Gothic Neo', 'Roboto', 'Noto Sans KR', 'Malgun Gothic', sans-serif;"
        );

        $breakpoint = array(
            'mobile-w' => '1024px',
        );

        $colors = array(
            'color-primary' => '#247bff',
            'color-secondary' => '#ff3a4e',

            'color-success' => '#13ce66',
            'color-warning' => '#f7ba2a',
            'color-danger' => '#ff5a57',
            'color-info' => '#2957ff',

            'color-white' => 'white',
            'color-black' => 'black',

            'color-gray-050' => '#fefefe',
            'color-gray-100' => '#fdfdfd',
            'color-gray-200' => '#f7f8f9',
            'color-gray-300' => '#e9ebee',
            'color-gray-400' => '#C5C8CE',
            'color-gray-500' => '#646f7c',
            'color-gray-600' => '#374553',
            'color-gray-700' => '#28323c',
            'color-gray-800' => '#161d24',

            'color-background' => 'white',
            'color-text-primary' => 'white'
        );

        $utils = array(
            'border-radius-1' => '6px',
            'border-radius-2' => '8px',
            'border-radius-3' => '12px',
        );

        self::$variables = array_merge($base, $font, $colors, $breakpoint, $utils);

        // 대표 색상
        self::$variables['color-primary'] = $config['primary_color'];

        // 최대폭
        if ($config['container_width']) {
            self::$variables['base-container'] = $config['container_width'];
        }

        // 폰트
        if ($config['font_family'] === 'noto_sans') {
            self::$variables['font-family'] = "'Noto Sans KR', 'Noto Sans Korean', -apple-system, 'SF Pro Display', 'Helvetica Neue',  'Apple SD Gothic Neo', 'Roboto', 'Noto Sans KR', 'Malgun Gothic', sans-serif";
        } elseif ($config['font_family'] === 'none') {
            self::$variables['font-family'] = "-apple-system, 'SF Pro Display', 'Helvetica Neue',  'Apple SD Gothic Neo', 'Roboto', 'Noto Sans KR', 'Malgun Gothic', sans-serif";
        }

        return self::$variables;
    }

    public static function colorScheme()
    {
        if (self::$colorScheme) {
            return self::$colorScheme;
        }

        $config = self::getConfig();

        $colorScheme = \Rhymix\Framework\UA::getColorScheme();
        if ($config['color_scheme'] !== 'auto') {
            $colorScheme = $config['color_scheme'];
            if ($config['color_scheme'] === 'light') {
                $key = array_search('color_scheme_dark', Context::getInstance()->body_class);
                unset(Context::getInstance()->body_class[$key]);
            } elseif ($config['color_scheme'] === 'dark') {
                $key = array_search('color_scheme_light', Context::getInstance()->body_class);
                unset(Context::getInstance()->body_class[$key]);
            }
            Context::addBodyClass('color_scheme_' . $config['color_scheme']);
        }

        self::$colorScheme = $colorScheme;
        return self::$colorScheme;
    }

    public static function isDarkMode()
    {
        $colorScheme = self::colorScheme();

        return $colorScheme === 'dark';
    }

    public static function loginIdentifier()
    {
        $memberConfig = \memberModel::getMemberConfig();
        $identifierTitle = [];
        $identifierField = [];

        foreach ($memberConfig->identifiers as $field) {
            $identifierTitle[] = Context::getLang($field);
            $identifierField[] = $field;
        }

        debugPrint([$identifierTitle, $identifierField]);
        $identifierTitle = implode(', ', $identifierTitle);

        if (count($identifierField) === 1) {
            $identifierField = $identifierField[0];
        }

        return [
            "title" => $identifierTitle,
            "field" => $identifierField
        ];
    }
}
