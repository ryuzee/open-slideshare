<?php

class AddCategoryData extends CakeMigration
{
    /**
     * Migration description.
     *
     * @var string
     */
    public $description = 'add_category_data';

    /**
     * Actions to be performed.
     *
     * @var array
     */
    public $migration = array(
        'up' => array(
        ),
        'down' => array(
        ),
    );

    /**
     * Before migration callback.
     *
     * @param string $direction Direction of migration process (up or down)
     *
     * @return bool Should process continue
     */
    public function before($direction)
    {
        return true;
    }

    /**
     * After migration callback.
     *
     * @param string $direction Direction of migration process (up or down)
     *
     * @return bool Should process continue
     */
    public function after($direction)
    {
        // Category data
        $Category = ClassRegistry::init('Category');
        if ($direction === 'up') {
            $category_data = array(
                '1' => 'Books',
                '2' => 'Business',
                '3' => 'Design',
                '4' => 'Education',
                '5' => 'Entertainment',
                '6' => 'Finance',
                '7' => 'Games',
                '8' => 'Health',
                '9' => 'How-to & DIY',
                '10' => 'Humor',
                '11' => 'Photos',
                '12' => 'Programming',
                '13' => 'Research',
                '14' => 'Science',
                '15' => 'Technology',
                '16' => 'Travel',
            );
            foreach ($category_data as $k => $v) {
                // add admin account to user table
                $data = array();
                $data['Category']['id'] = $k;
                $data['Category']['name'] = $v;
                $Category->create();
                if ($Category->save($data)) {
                    $this->callback->out('Category data was created...');
                } else {
                    echo 'Category data creation failed......';
                }
            }
        }

        return true;
    }
}
