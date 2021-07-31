<?php

/**
 * Copyright 2016 LINE Corporation
 *
 * LINE Corporation licenses this file to you under the Apache License,
 * version 2.0 (the "License"); you may not use this file except in compliance
 * with the License. You may obtain a copy of the License at:
 *
 *   https://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */

require_once('./LINEBotTiny.php');

$channelAccessToken = 'hyvRi6aeylkPE+yM45+2Tkc9g/MVfk8uUUmd22RAmDUjLj8ye7f4NuUqRUaPN1X9B4GEeQUKvh/oT8ARoYuUqU3AU2Yg6YuyelM1ufzF1QfNl6/iMWDXgsvNe+74UbVmUJc8O3irFotNWNXzIJ//AgdB04t89/1O/w1cDnyilFU=';
$channelSecret = '43f84ba5e98d729eb1b3572c9ce36bf3';

$client = new LINEBotTiny($channelAccessToken, $channelSecret);
foreach ($client->parseEvents() as $event) {
	switch ($event['type']) {
		case 'message':
			$message = $event['message'];
			switch ($message['type']) {
				case 'text':
					$reply['replyToken'] = $event['replyToken'];
					$reply['messages'][] = ['type' => 'text', 'text' => $message['text']];
					$client->replyMessage($reply);
					break;
				case 'location':
					$reply['replyToken'] = $event['replyToken'];
					require_once('hot_pepper.php');
					$client->replyMessage($reply);
					break;
				default:
					error_log('Unsupported message type: ' . $message['type']);
					break;
			}
			break;
		default:
			error_log('Unsupported event type: ' . $event['type']);
			break;
	}
};
