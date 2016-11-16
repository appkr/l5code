<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class InitializeDockerContainer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'my:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Docker 컨테이너에 설치된 MySQL에 myapp 데이터베이스와 homestead 사용자를 만들고, 마이그레이션과 시딩을 합니다(이 명령은 Docker 컨테이너에서 실행해야 합니다).';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            system('/bin/bash ' . base_path('docker/myinit.sh'));
        } catch (Exception $e) {
            $this->error('실패했습니다.:' . $e->getMessage());
            exit;
        }

        $this->info('초기화 완료');
    }
}
