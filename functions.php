<?php

// enqueue parent styles

function ns_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}

add_action( 'wp_enqueue_scripts', 'ns_enqueue_styles' );

function custom_category_cloud_by_slugs($atts) {
    $atts = shortcode_atts(array(
        'category_slugs' => '',
    ), $atts);

    // カンマ区切りのスラッグを配列に変換
    $slugs = explode(',', $atts['category_slugs']);
    $category_ids = array();

    // 各スラッグに対応するカテゴリーIDを取得
    foreach ($slugs as $slug) {
        $category = get_category_by_slug(trim($slug));
        if ($category) {
            $category_ids[] = $category->term_id;
        }
    }

    // IDを基にカテゴリーを取得
    $categories = get_categories(array(
        'include' => $category_ids,
        'orderby' => 'name',
        'order'   => 'ASC'
    ));

    $output = '<div class="category-cloud">';

    foreach ($categories as $category) {
        $font_size = 8 + ($category->count / 2);
        if ($font_size > 18) $font_size = 18;

        // カテゴリーリンクにマージンを追加
        $output .= sprintf(
            '<a href="%1$s" style="font-size:%2$spx; margin-right: 10px; margin-bottom: 10px;">%3$s (%4$s)</a> ',
            esc_url(get_category_link($category->term_id)),
            $font_size,
            esc_html($category->name),
            $category->count
        );
    }

    $output .= '</div>';
    return $output;
}
add_shortcode('custom_category_cloud', 'custom_category_cloud_by_slugs');

function custom_avatar_by_persona($avatar, $id_or_email, $size, $default, $alt) {
    // ペルソナと対応するカスタムアバターURLの配列
    $persona_avatars = array(
    'Raj Patel（AIペルソナ）' => 'https://innovatopia.jp/wp-content/uploads/2023/12/Raj-Patel-e1703649341899.png',
    'Carlos Gutierrez（AIペルソナ）' => 'https://innovatopia.jp/wp-content/uploads/2023/12/Carlos-Gutierrez-e1703649305546.png',
    'Emeka Okonkwo（AIペルソナ）' => 'https://innovatopia.jp/wp-content/uploads/2023/12/Emeka-Okonkwo-e1703649271545.png',
    'Maya Johnson（AIペルソナ）' => 'https://innovatopia.jp/wp-content/uploads/2023/12/Maya-Johnson-e1703649367684.png',
    'Hiro Tanaka（AIペルソナ）' => 'https://innovatopia.jp/wp-content/uploads/2023/12/Hiro-Tanaka-e1703649249566.png',
    'Nia Johnson（AIペルソナ）' => 'https://innovatopia.jp/wp-content/uploads/2023/12/Nia-Johnson-e1703649355383.png',
    'Elena Ivanova（AIペルソナ）' => 'https://innovatopia.jp/wp-content/uploads/2023/12/Elena-Ivanova-e1703649287770.png',
    'Lars Svensson（AIペルソナ）' => 'https://innovatopia.jp/wp-content/uploads/2023/12/Lars-Svensson-e1703649377222.png',
    'Sarah Goldberg（AIペルソナ）' => 'https://innovatopia.jp/wp-content/uploads/2023/12/Sarah-Goldberg-e1703649328633.png',
    'Zhang Wei（AIペルソナ）' => 'https://innovatopia.jp/wp-content/uploads/2023/12/Zhang-Wei-e1703649317217.png',
    'Emma Lee（AIペルソナ）' => 'https://innovatopia.jp/wp-content/uploads/2023/12/Emma-Lee-e1703655374502.png',
    'Javier Garcia（AIペルソナ）' => 'https://innovatopia.jp/wp-content/uploads/2023/12/Javier-Garcia-e1703655383145.png',
    'Olivia Janson（AIペルソナ）' => 'https://innovatopia.jp/wp-content/uploads/2023/12/Olivia-Janson-e1703655391950.png',
    'Emilie Dubois（AIペルソナ）' => 'https://innovatopia.jp/wp-content/uploads/2023/12/Emilie-Dubois-e1703655355100.png',
    'Anita Patel（AIペルソナ）' => 'https://innovatopia.jp/wp-content/uploads/2023/12/Anita-Patel-e1703655325591.png',
    'John Smith（AIペルソナ）' => 'https://innovatopia.jp/wp-content/uploads/2023/12/John-Smith-e1703655336844.png',
    'Susan Johnson（AIペルソナ）' => 'https://innovatopia.jp/wp-content/uploads/2023/12/Susan-Johnson-e1703655344943.png',
    'Alex Gonzalez（AIペルソナ）' => 'https://innovatopia.jp/wp-content/uploads/2023/12/Alex-Gonzalez-e1703655364213.png',
    'Emily Zhou（AIペルソナ）' => 'https://innovatopia.jp/wp-content/uploads/2023/12/Emily-Zhou-e1703664211819.png',
    'Takashi Yamamoto（AIペルソナ）' => 'https://innovatopia.jp/wp-content/uploads/2023/12/Takashi-Yamamoto-e1703664226743.png',
    '趙 翔太（AIペルソナ）' => 'https://innovatopia.jp/wp-content/uploads/2023/12/-翔太-e1703822207465.png',
    '高橋 一樹（AIペルソナ）' => 'https://innovatopia.jp/wp-content/uploads/2023/12/-一樹-e1703822219978.png',
    '中村 海斗（AIペルソナ）' => 'https://innovatopia.jp/wp-content/uploads/2023/12/Kaito-Nakamura-e1703822230806.png',
    '田中優子（AIペルソナ）' => 'https://innovatopia.jp/wp-content/uploads/2023/12/Yuko-Tanaka-e1703822241348.png',
    '田中 陽人（AIペルソナ）' => 'https://innovatopia.jp/wp-content/uploads/2023/12/Haruto-Tanaka-e1703822255205.png',
    '加藤 修一（AIペルソナ）' => 'https://innovatopia.jp/wp-content/uploads/2023/12/-修一-e1703837188832.png',
    '山本 広行（AIペルソナ）' => 'https://innovatopia.jp/wp-content/uploads/2023/12/-広行-e1703837199365.png',
    '中村 陽太（AIペルソナ）' => 'https://innovatopia.jp/wp-content/uploads/2023/12/-陽太-e1703837709448.png'
);


    // ユーザー名またはメールアドレスに基づいてチェック
    if (is_object($id_or_email)) {
        $user_name = $id_or_email->comment_author;
    } else {
        $user = get_user_by('email', $id_or_email);
        $user_name = $user ? $user->user_login : '';
    }

    // ペルソナ名が一致する場合、カスタムアバターを使用
    if (array_key_exists($user_name, $persona_avatars)) {
        $custom_avatar_url = $persona_avatars[$user_name];
        $avatar = "<img alt='{$alt}' src='{$custom_avatar_url}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
    }

    return $avatar;
}

// フィルターフックにカスタム関数を追加
add_filter('get_avatar', 'custom_avatar_by_persona', 10, 5);

function auto_approve_specific_ipv6_comment( $approved, $commentdata ) {
    $trusted_ipv6_prefix = '2600:1900:2000'; // 信頼できるIPv6アドレスの最初の12桁
    $user_ip = $commentdata['comment_author_IP'];

    if ( strpos( $user_ip, $trusted_ipv6_prefix ) === 0 ) {
        return 1; // コメントを承認
    }

    return $approved;
}

add_filter( 'pre_comment_approved', 'auto_approve_specific_ipv6_comment', 99, 2 );

// ces2024用の変更
function add_custom_text_to_posts( $content ) {
    if ( is_single() ) {
        // CES 2024に関連するテキストを追加
        if ( has_tag('ces2024') ) {
            $custom_text_ces2024 = '<div><br>
                <strong><a href="https://innovatopia.jp/tag/ces2024/">【CES 2024 記事一覧】未来のプロダクトが一堂に！ＳＦが現実になる</a></strong>
                <strong><a href="https://innovatopia.jp/tech-social/tech-social-news/6783/">【CESを解説】なぜ、世界中のハイテク企業がここで製品を発表するのか？</a></strong>
                </div>';
            $content .= $custom_text_ces2024;
        }

        // ダボス会議に関連するテキストを更新
        if ( has_tag('davos') ) {
            $custom_text_davos = '<div><br>
                <a href="https://innovatopia.jp/tag/davos/"><strong>【ダボス会議 記事一覧】どうなる？先端テクノロジーが導く人類の将来</strong></a>
                </div>';
            $content .= $custom_text_davos;
        }

        // ETF承認に関連するテキストを更新
        if ( has_tag('etf') ) {
            $custom_text_etf = '<div><br>
                <a href="https://innovatopia.jp/tag/etf/"><strong>【ETF承認 記事一覧】ビットコイン投資が身近に。今後の展開は？</strong></a>
                </div>';
            $content .= $custom_text_etf;
        }

        // ビットコイン半減期に関連するテキストを更新
        if ( has_tag('halving') ) {
            $custom_text_halving = '<div><br>
                <a href="https://innovatopia.jp/tag/halving/"><strong>【半減期 記事一覧】チャートを見れば一目瞭然。４年に一度はもうすぐ</strong></a></div>
				<div><strong><a href="https://innovatopia.jp/blockchain/bitcoin/9159/">【BTC半減期を解説】価格への影響は？ビットコイン半減期2024について解説</a></strong>
                </div>';
            $content .= $custom_text_halving;
        }

        // 著作権に関連するテキストを更新
        if ( has_tag('copyright') ) {
            $custom_text_copyright = '<div><br>
                <a href="https://innovatopia.jp/tag/copyright/"><strong>【著作権 記事一覧】解釈や法律、運用方法が変わるかもしれない</strong></a>
                </div>';
            $content .= $custom_text_copyright;
        }
		// お昼のニュースピックアップに関連するテキストを追加
        if ( has_tag('ohiru') ) {
            $custom_text_ohiru = '<div><br>
                <a href="https://innovatopia.jp/tag/ohiru/"><strong>【お昼のニュースピックアップ 記事一覧】日々の動きを手軽にチェック。話題はここから</strong></a>
                </div>';
            $content .= $custom_text_ohiru;
        }

        // AIと教育に関連するテキストを更新
        if ( has_tag('education') ) {
            $custom_text_education = '<div><br>
                <a href="https://innovatopia.jp/tag/education/"><strong>【生成AIと教育 記事一覧】AIによる教育革命。その効用とリスクとは</strong></a>
                </div>';
            $content .= $custom_text_education;
        }
    }
    return $content;
}
add_filter( 'the_content', 'add_custom_text_to_posts' );



function add_custom_text_to_autonews_posts( $content ) {
    if ( is_single() && has_tag('autonews') ) {
        $custom_text = '<div><br>
            <p>AIペルソナの紹介は<a href="https://innovatopia.jp/ai-persona/">こちら</a></p>
        </div>';

        if ( in_category('ai-news') ) {
    $custom_text .= '<div><a href="https://innovatopia.jp/ai/">AI（人工知能）に関する概説</a></div><br></br>';
}
        if ( in_category('bio-news') ) {
    $custom_text .= '<div><a href="https://innovatopia.jp/biotechnology/">バイオテクノロジーに関する概説</a></div><br></br>';
}
		if ( in_category('blockchain-news') ) {
    $custom_text .= '<div><a href="https://innovatopia.jp/blockchain/">ブロックチェーンに関する概説</a></div><br></br>';
}


        $content .= $custom_text;
    }
    return $content;
}
add_filter( 'the_content', 'add_custom_text_to_autonews_posts' );

function custom_ogp_for_category() {
    if (is_category('ai') && !is_singular()) {
        echo '<meta property="og:title" content="AI（人工知能）テクノロジー概説" />';
        echo '<meta property="og:description" content="AI（人工知能）の歴史、現在地、今後の展望や課題などを解説しています。" />';
    } elseif (is_category('blockchain') && !is_singular()) {
        echo '<meta property="og:title" content="ブロックチェーンテクノロジー概説" />';
        echo '<meta property="og:description" content="ビットコインを代表とする分散型台帳技術でデータを管理する、新しいインターネットの仕組みを解説しています。"/>';
    } elseif (is_category('biotechnology') && !is_singular()) {
        echo '<meta property="og:title" content="バイオテクノロジー概説" />';
        echo '<meta property="og:description" content="生物学的プロセスを利用して、医療、農業、工業などの分野へ応用する技術を解説しています。" />';
    }
}
add_action('wp_head', 'custom_ogp_for_category');

function add_explanation_link($content) {
    if (is_single() && get_the_ID() > 8000) {
        $tags = wp_get_post_tags(get_the_ID());
        $has_autonews_tag = false;

        foreach ($tags as $tag) {
            if ($tag->name == 'autonews') {
                $has_autonews_tag = true;
                break;
            }
        }

        if ($has_autonews_tag) {
            $explanation_link = '<div><a href="#explanation">&#12304;解説モードで読む&#12305;</a></div>';
            $content = $explanation_link . $content;
        }
    }

    return $content;
}
add_filter('the_content', 'add_explanation_link');

function my_post_published_action( $new_status, $old_status, $post ) {
    if ( 'pending' === $old_status && 'publish' === $new_status ) {
        // 定義されたエンドポイントURLを使用
        $endpoint_url = MY_ENDPOINT_URL;

        // 記事のURLを取得
        $post_url = get_permalink($post->ID);

        // 記事のカテゴリー名を取得
        $categories = get_the_category($post->ID);
        $category_names = array();
        foreach ($categories as $category) {
            array_push($category_names, $category->name);
        }

        // 記事のコンテンツを取得
        $post_content = $post->post_content;

        // HTTPリクエストのボディを構築
        $body = array(
            'url' => $post_url,
            'categories' => $category_names,
            'content' => $post_content
        );

        // HTTPリクエストの送信
        wp_remote_post($endpoint_url, array(
            'method'      => 'POST',
            'body'        => $body
        ));
    }
}

add_action( 'transition_post_status', 'my_post_published_action', 10, 3 );

