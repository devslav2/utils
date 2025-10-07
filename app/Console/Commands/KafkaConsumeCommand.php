<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use RdKafka\KafkaConsumer;
use RdKafka\Conf;
use App\Jobs\ConsumeKafkaMessage;

class KafkaConsumeCommand extends Command
{
    protected $signature = 'kafka:consume';
    protected $description = 'Consumer Kafka in ascolto continuo';

    public function handle()
    {
        $conf = new Conf();
        $conf->set('group.id', config('message_queue.kafka.consumer_group'));
        $conf->set('bootstrap.servers', config('message_queue.kafka.brokers'));
        $conf->set('auto.offset.reset', 'earliest');

        $consumer = new KafkaConsumer($conf);
        $consumer->subscribe([config('message_queue.kafka.topic')]);

        $this->info("Kafka consumer in ascolto...");

        while (true) {
            $message = $consumer->consume(1000);

            switch ($message->err) {
                case RD_KAFKA_RESP_ERR_NO_ERROR:
                    ConsumeKafkaMessage::dispatch($message->payload);
                    $this->info("Messaggio ricevuto: " . $message->payload);
                    break;

                case RD_KAFKA_RESP_ERR__PARTITION_EOF:
                case RD_KAFKA_RESP_ERR__TIMED_OUT:
                    break;

                default:
                    $this->error("Errore Kafka: " . $message->errstr());
                    break;
            }
        }
    }
}