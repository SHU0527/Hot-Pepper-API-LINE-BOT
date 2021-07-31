<?php

$lng = $message['longitude'];
$lat = $message['latitude'];


$url = "http://webservice.recruit.co.jp/hotpepper/gourmet/v1/?key=ac57fc4d2f621cee&lat={$lat}&lng={$lng}&range=3&order=4&format=json";
$ch = curl_init();


curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$get_url_result = curl_exec($ch);
$json_data = json_decode($get_url_result, true);


curl_close($ch);

// 各データの最上位にresult階層があるので更に繰り返し処理
foreach ($json_data['results']['shop'] as $data) {
    // タイトルが40文字以上の場合はトリミング
	if (mb_strlen($data['name']) > 40) {
		$str_t = str_replace(PHP_EOL, '', $data['name']);
		$str_t = preg_split('//u', $str_t, 0, PREG_SPLIT_NO_EMPTY);
		$title = '';
		for ($i = 0; $i < 37; $i++) {
			$title .= $str_t[$i];
		}
		$title .= '...';
	} else {
		$title = str_replace(PHP_EOL, '', $data['name']);
	}

		// 説明が60文字以上の場合はトリミング
	if (mb_strlen($data['address']) > 60) {
		$str_d = str_replace(PHP_EOL, '', $data['address']);
		$str_d = preg_split('//u', $str_d, 0, PREG_SPLIT_NO_EMPTY);
		$description = '';
		for ($i = 0; $i < 57; $i++) {
			$description .= $str_d[$i];
		}
		$description .= '...';
	} else {
		$description = str_replace(PHP_EOL, '', $data['address']);
	}

		// カラムオブジェクトの生成
	$columns[] = [
		'thumbnailImageUrl' => $data['logo_image'],
		'title'   => $title,
		'text'    => $description,
		'actions' => [
			[
				'type' => 'uri',
				'uri' => $data['urls']['pc'],
				'label' => '詳細はこちら>>'
			]
		]
	];
}

$template = ['type' => 'carousel', 'columns' => $columns];
$reply['messages'][] = ['type' => 'template', 'altText' => 'すみません...', 'template' => $template];

?>

