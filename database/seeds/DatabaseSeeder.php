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

        foreach(array_transpose($tags) as $slug => $names) {
            App\Tag::create([
                'name' => $names['ko'],
                'ko' => $names['ko'],
                'en' => $names['en'],
                'slug' => str_slug($slug)
            ]);
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
        if (config('database.default') != 'testing') {
            // testing 환경이 아닐 때만...
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

            foreach(range(1, 10) as $index) {
                // 테스트를 위해 고아가 된 첨부파일을 만든다.
                // 고아가 된 첨부파일 이란 article_id가 없고 생성된 지 일주일 넘은 테이블 레코드/파일를 의미한다.
                $path = $faker->image(attachments_path());
                $filename = File::basename($path);
                $bytes = File::size($path);
                $mime = File::mimeType($path);
                $this->command->warn("File saved: {$filename}");

                factory(App\Attachment::class)->create([
                    'filename' => $filename,
                    'bytes' => $bytes,
                    'mime' => $mime,
                    'created_at' => $faker->dateTimeBetween('-1 months'),
                ]);
            }

            $this->command->info('Seeded: attachments table and files');
        }

        /* 댓글 */
        $articles->each(function ($article) {
            $article->comments()->save(factory(App\Comment::class)->make());
            $article->comments()->save(factory(App\Comment::class)->make(
                ['deleted_at' => Carbon\Carbon::now()->toDateTimeString()]
            ));
        });

        // 댓글의 댓글(자식 댓글)
        $articles->each(function ($article) use ($faker){
            $commentIds = App\Comment::pluck('id')->toArray();
            $now = Carbon\Carbon::now()->toDateTimeString();

            foreach(range(1,5) as $index) {
                $article->comments()->save(
                    factory(App\Comment::class)->make([
                        'parent_id' => $faker->randomElement($commentIds),
                        'deleted_at' => $faker->optional()->randomElement([null, $now]),
                    ])
                );
            }
        });

        $this->command->info('Seeded: comments table');

        /* up & down 투표 */
        if (config('database.default') != 'testing') {
            // testing 환경이 아닐 때만...
            // Sqlite가 up, down 열에 대해 'Integrity constraint violation' 일으킴
            $comments = App\Comment::all();

            $comments->each(function ($comment) {
                $comment->votes()->save(factory(App\Vote::class)->make());
                $comment->votes()->save(factory(App\Vote::class)->make());
                $comment->votes()->save(factory(App\Vote::class)->make());
            });
        }

        $this->command->info('Seeded: votes table');
    }
}
