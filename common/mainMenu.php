<?php
// common/header.php
// ВСЁ меню в одном файле: данные + отображение

// ============================================
// 1. ДАННЫЕ МЕНЮ (в PHP массиве)
// ============================================

$menuItems = [
    'download' => [
        'name' => ['ru' => 'Загрузки', 'en' => 'Download'],
        'description' => [
            'ru' => 'Установочные пакеты для Windows и Linux. Последняя версия, архив версий.',
            'en' => 'Installation packages for Windows and Linux. Latest version, version archive.'
        ],
        'submenu' => [
            'latest' => [
                'name' => ['ru' => 'Последняя версия', 'en' => 'Latest version'],
                'description' => ['ru' => 'Актуальная стабильная сборка', 'en' => 'Current stable build']
            ],
            'archive' => [
                'name' => ['ru' => 'Архив версий', 'en' => 'Version archive'],
                'description' => ['ru' => 'Все предыдущие релизы', 'en' => 'All previous releases']
            ],
            'download#linux' => [
                'name' => ['ru' => 'Системные требования', 'en' => 'System requirements'],
                'description' => ['ru' => 'Аппаратные и программные требования', 'en' => 'Hardware and software requirements']
            ]
        ]
    ],
    'about' => [
        'name' => ['ru' => 'О проекте', 'en' => 'About'],
        'description' => [
            'ru' => 'История создания, возможности программы, дорожная карта',
            'en' => 'History, features, roadmap'
        ],
        'submenu' => [
            'features' => [
                'name' => ['ru' => 'Возможности', 'en' => 'Features'],
                'description' => ['ru' => 'Полный перечень функций', 'en' => 'Complete feature list']
            ],
            'news' => [
                'name' => ['ru' => 'Новости', 'en' => 'News'],
                'description' => ['ru' => 'Обновления и последние события проекта', 'en' => 'Project updates and latest developments']
            ],
            'history' => [
                'name' => ['ru' => 'История проекта', 'en' => 'Project history'],
                'description' => ['ru' => 'Более 25 лет развития', 'en' => 'Over 25 years of development']
            ]
        ]
    ],
    'help' => [
        'name' => ['ru' => 'Помощь', 'en' => 'Help'],
        'description' => [
            'ru' => 'Документация, видеоуроки, библиотеки, поддержка',
            'en' => 'Documentation, tutorials, libraries, support'
        ],
        'submenu' => [
            'doc/userGuide' => [
                'name' => ['ru' => 'Документация', 'en' => 'Documentation'],
                'description' => ['ru' => 'Полное руководство пользователя', 'en' => 'Complete user manual']
            ],
            'videos' => [
                'name' => ['ru' => 'Видеоуроки', 'en' => 'Video tutorials'],
                'description' => ['ru' => 'Наглядные обучающие ролики', 'en' => 'Visual tutorial videos']
            ],
            'articles' => [
                'name' => ['ru' => 'Статьи', 'en' => 'Articles'],
                'description' => ['ru' => 'Статьи, практические примеры использования системы', 'en' => 'Articles, practical guides and feature tutorials']
            ],
            'support' => [
                'name' => ['ru' => 'Поддержка', 'en' => 'Support'],
                'description' => ['ru' => 'Помощь и обратная связь', 'en' => 'Help and feedback']
            ]
        ]
    ]
];

// ============================================
// 2. СПИСОК ЯЗЫКОВ (легко расширять)
// ============================================

$languages = [
    'ru' => ['name' => 'Русский', 'flag' => '🇷🇺'],
    'en' => ['name' => 'English', 'flag' => '🇬🇧'],
    // Добавить новые языки просто:
    // 'de' => ['name' => 'Deutsch', 'flag' => '🇩🇪'],
    // 'fr' => ['name' => 'Français', 'flag' => '🇫🇷'],
];



?>