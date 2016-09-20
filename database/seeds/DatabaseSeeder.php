<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sqlite = in_array(config('database.default'), ['sqlite', 'testing'], true);

        if (! $sqlite) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
        }

        /* 태그 */
        App\Tag::truncate();
        DB::table('article_tag')->truncate();
        $tags = config('project.tags');

        foreach($tags as $slug => $name) {
            App\Tag::create([
                'name' => $name,
                'slug' => str_slug($slug) ]
            );
        }
        $this->command->info('Seeded: tags table');

        if (! app()->environment(['production'])) {
            // 운영 환경이 아닐 때만 나머지 시딩을 실행한다.
            $this->seedForDev();
        }

        if (! $sqlite) {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }

    protected function seedForDev()
    {
        /* User */
        $this->call(UsersTableSeeder::class);

        /* 아티클 */
        $this->call(ArticlesTableSeeder::class);

        // 변수 선언
        $faker = app(Faker\Generator::class);
        $users = App\User::all();
        $articles = App\Article::all();
        $tags = App\Tag::all();

        // 아티클과 태그 연결
        foreach($articles as $article) {
            $article->tags()->sync(
                $faker->randomElements(
                    $tags->pluck('id')->toArray(),
                    rand(1, 3)
                )
            );
        }
        $this->command->info('Seeded: article_tag table');

        /* 첨부 파일 */
        App\Attachment::truncate();

        if (! File::isDirectory(attachments_path())) {
            File::makeDirectory(attachments_path(), 775, true);
        }

        File::cleanDirectory(attachments_path());

        // public/files/.gitignore 파일이 있어야 커밋할 때 빈 디렉터리를 유지할 수 있다.
        File::put(attachments_path('.gitignore'), "*\n!.gitignore");

        $this->command->error(
            'Downloading ' . $articles->count() . ' images from lorempixel. It takes time...'
        );

        $articles->each(function ($article) use ($faker) {
            $path = $faker->image(attachments_path());
            $filename = File::basename($path);
            $bytes = File::size($path);
            $mime = File::mimeType($path);
            $this->command->warn("File saved: {$filename}");

            $article->attachments()->save(
                factory(App\Attachment::class)->make(compact('filename', 'bytes', 'mime'))
            );
        });
        $this->command->info('Seeded: attachments table and files');
    }
}
