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
            $this->command->warn("Creating file: {$filename}");

            $article->attachments()->save(
                factory(App\Attachment::class)->make(compact('filename', 'bytes', 'mime'))
            );
        });

        $this->command->info('Seeded: attachments table and files');
    }
}
