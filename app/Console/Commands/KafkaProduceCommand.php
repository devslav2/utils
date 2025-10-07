<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use RdKafka\Producer;

class KafkaProduceCommand extends Command
{
    protected $signature = 'kafka:produce {message}';
    protected $description = 'Produce a message to Kafka topic';

    public function handle()
    {
        $message = $this->argument('message');

        $conf = new \RdKafka\Conf();
        // Imposta direttamente bootstrap.servers
        $conf->set('bootstrap.servers', config('kafka.brokers'));

        $producer = new Producer($conf);

        $topic = $producer->newTopic(config('kafka.topic'));
        $topic->produce(RD_KAFKA_PARTITION_UA, 0, $message);
        $producer->flush(10000);

        $this->info("Message sent to Kafka: $message");
    }
}