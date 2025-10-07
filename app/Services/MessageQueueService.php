<?php

namespace App\Services;

use RdKafka\Producer;
use RdKafka\Conf as KafkaConf;
use App\Jobs\ConsumeKafkaMessage;

class MessageQueueService
{
    public function send(string $message)
    {
        $driver = config('message_queue.driver');

        if ($driver === 'kafka') {
            $conf = new KafkaConf();
            $conf->set('bootstrap.servers', config('message_queue.kafka.brokers'));

            if (config('message_queue.kafka.username')) {
                $conf->set('security.protocol', 'SASL_SSL');
                $conf->set('sasl.mechanisms', 'PLAIN');
                $conf->set('sasl.username', config('message_queue.kafka.username'));
                $conf->set('sasl.password', config('message_queue.kafka.password'));
            }

            $producer = new Producer($conf);
            $topic = $producer->newTopic(config('message_queue.kafka.topic'));
            $topic->produce(RD_KAFKA_PARTITION_UA, 0, $message);
            $producer->flush(10000);

        } elseif ($driver === 'redis') {
            ConsumeKafkaMessage::dispatch($message);
        }
    }
}