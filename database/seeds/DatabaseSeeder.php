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

        /* Tag */
        App\Tag::truncate();
        DB::table('article_tag')->truncate();
        $tags = config('project.tags');

        foreach($tags as $slug => $name) {
            App\Tag::create([
                'name' => $name,
                'slug' => str_slug($slug)
            ]);
        }
        $this->command->info('Seeded: tags table');

        if (app()->environment(['local', 'testing'])) {
            $this->seedForDev();
        }

        /* End */
        if (! $sqlite) {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }

    /**
     * Run the seeds which applicable only to development env.
     */
    private function seedForDev()
    {
        /* User */
        App\User::truncate();
        $this->call(UsersTableSeeder::class);

        /* Article */
        App\Article::truncate();
        $this->call(ArticlesTableSeeder::class);

        /* Base Variables */
        $faker = app(Faker\Generator::class);
        $users = App\User::all();
        $articles = App\Article::all();
        $tags = App\Tag::all();

        /* Attach Tags */
        foreach($articles as $article) {
            $article->tags()->sync(
                $faker->randomElements(
                    $tags->pluck('id')->toArray(), rand(1, 3)
                )
            );
        }
        $this->command->info('Seeded: article_tag table');

        /* Attachment */
        App\Attachment::truncate();

        if (! File::isDirectory(attachments_path())) {
            File::makeDirectory(attachments_path(), 777, true);
        }

        File::cleanDirectory(attachments_path());

        $this->command->error('Downloading images from lorempixel. It takes time...');

        $articles->each(function($article) use ($faker) {
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

        /* Comments */
        $articles->each(function($article) {
            $article->comments()->save(factory(App\Comment::class)->make());
            $article->comments()->save(factory(App\Comment::class)->make(
                ['deleted_at' => Carbon\Carbon::now()->toDateTimeString()]
            ));
        });

        // Children comments
        $articles->each(function($article) use ($faker){
            $commentIds = App\Comment::pluck('id')->toArray();

            foreach(range(1,5) as $index) {
                $now = Carbon\Carbon::now()->toDateTimeString();

                $article->comments()->save(
                    factory(App\Comment::class)->make([
                        'parent_id' => $faker->randomElement($commentIds),
                        'deleted_at' => $faker->optional()->randomElement([null, $now]),
                    ])
                );
            }
        });

        $this->command->info('Seeded: comments table');

        /* Vote */
        $comments = App\Comment::all();

        $comments->each(function($comment) {
            $comment->votes()->save(factory(App\Vote::class)->make());
            $comment->votes()->save(factory(App\Vote::class)->make());
            $comment->votes()->save(factory(App\Vote::class)->make());
        });

        $this->command->info('Seeded: votes table');
    }
}
