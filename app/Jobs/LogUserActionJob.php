<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;
use ClickHouseDB\Client;

class LogUserActionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    protected array $logData;

    /**
     * Создание нового экземпляра job.
     *
     * @param array $logData Данные для логирования
     */
    public function __construct(array $logData)
    {
        $this->logData = $logData;
    }

    /**
     * Выполнение задачи.
     */
    public function handle(): void
    {
        $config = [
            'host' => env('CLICKHOUSE_HOST', '127.0.0.1'),
            'port' => env('CLICKHOUSE_PORT', 8123),
            'username' => env('CLICKHOUSE_USERNAME', 'default'),
            'password' => env('CLICKHOUSE_PASSWORD', '123456'),
            'database' => env('CLICKHOUSE_DATABASE', 'silkroad_tursbor'),
        ];

        try {
            $client = new Client($config);

            $data = [
                'id' => $this->logData['id'],
                'user_id' => $this->logData['user_id'],
                'user_name' => $this->logData['user_name'],
                'hotel_id' => $this->logData['hotel_id'],
                'hotel_name' => $this->logData['hotel_name'],
                'data' => json_encode($this->logData['data']),
                'modul_id' => $this->logData['modul_id'],
                'modul' => $this->logData['modul'],
                'event' => $this->logData['event'],
                'ip_address' => $this->logData['ip_address'],
                'created_at' => $this->logData['created_at'] instanceof \DateTime
                    ? $this->logData['created_at']->format('Y-m-d H:i:s')
                    : (string)$this->logData['created_at'],
                'dt' => isset($this->logData['dt'])
                    ? $this->logData['dt']
                    : (isset($this->logData['created_at']) && $this->logData['created_at'] instanceof \DateTime
                        ? $this->logData['created_at']->format('Y-m-d')
                        : (string)$this->logData['created_at']),
            ];

            $client->insert('silkroad_tursbor.clickhouse_audit', [$data]);


        } catch (\Exception $e) {
            \Log::error('Ошибка записи в ClickHouse: ' . $e->getMessage());
        }
    }
}
