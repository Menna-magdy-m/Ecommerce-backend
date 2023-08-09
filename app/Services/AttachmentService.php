<?php


namespace App\Services;


use App\Dtos\Result;
use App\Models\Banner;
use App\Models\Attachment;
use App\Models\WishListProduct;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use File;
use Illuminate\Support\Facades\URL;

class AttachmentService extends ModelService
{
    /**
     * storable field is a field which can be filled during creating the record
     */
    protected array $storables = [
        'content',
        'user_id',
        'title',
        'status',
        'post_parent',
        'path',
        'parent_type',
        'menu_order',
        'post_mime_type'
    ];

    /**
     * updatable field is a field which can be filled during updating the record
     */
    protected array $updatables = [
        'content',
        'title',
        'status',
        'post_parent',
        'path',
        'parent_type',
        'menu_order',
        'post_mime_type'
    ];

    /**
     * searchable field is a field which can be search for from keyword parameter in search method
     */
    protected array $searchables = ['title', 'post_parent', 'parent_type'];
    /**
     *
     */
    protected array $with = [];

    public function builder(): \Illuminate\Database\Eloquent\Builder
    {
        return Attachment::query();
    }

    /**
     * prepare
     */
    protected function prepare(string $operation, array $attributes): array
    {

        return parent::prepare($operation, $attributes);
    }

    /**
     * create a new attachment
     */
    public function store(array $attributes): Attachment
    {
        $record = parent::store($attributes);
        //dd($record);
        return $record;
    }


    /**
     * create a new attachment
     */
    public function create(array $attributes)
    {
        $file = $attributes['file'];
        $user = auth()->user();
        $fileSaveAsName = time() . rand(1000, 9999) . "-attach." . $file->getClientOriginalExtension();
        $upload_path = public_path() . '/attachments/' . $user->ID . '/';

        $file_url = $upload_path . $fileSaveAsName;
        $success = $file->move($upload_path, $fileSaveAsName);
        $user_file = URL::to('/') . '/attachments/' . $user->ID . '/' . $fileSaveAsName;

        $attachment = [
            'title' => $attributes['title'],
                'user_id' => $user->ID,
            'content' => $attributes['content'],
            'parent_type' => $attributes['parent_type'],
            'menu_order' => $attributes['menu_order'],
            'path' => $user_file,
            'post_parent' => array_key_exists("post_parent", $attributes) ? $attributes['post_parent'] : null,
        ];


        $record = $this->store($attachment);
        return $record;
    }
    /**
     * update attachment
     */
    public function save($banner_ID, array $attributes)
    {

    }

    /**
     *update banner
     *
     */
    public function update2(int $id, array $attributes)
    {
        //dd($attributes);
        $record = Attachment::findOrFail($id);


        if (array_key_exists("file", $attributes)) {
            $oldPath = public_path() . $record->post_content; //delete old file first
            // if (File::exists($oldPath)) {
            //     unlink($oldPath);
            // }
            $file = $attributes['file'];
            $user = auth()->user();
            $fileSaveAsName = time() . rand(1000, 9999) . "-attach." . $file->getClientOriginalExtension();
            $upload_path = public_path() . '/attachments/' . $user->ID . '/';

            $file_url = $upload_path . $fileSaveAsName;
            $success = $file->move($upload_path, $fileSaveAsName);
            $user_file = '/attachments/' . $user->ID . '/' . $fileSaveAsName;
            $record->update([
                'title' => $attributes['title'],
                'content' => $attributes['content'],
                'parent_type' => $attributes['parent_type'],
                'menu_order' => $attributes['menu_order'],
                'path' => $user_file,
                'post_parent' => array_key_exists("post_parent", $attributes) ? $attributes['post_parent'] : null,
            ]);

        }


        return $this->ok($record, "attachment updated");
    }

    public function destroy($id)
    {
        $record = $this->find($id);
        if ($record instanceof Attachment) {
            $oldPath = public_path() . $record->post_content; //delete  file
            if (File::exists($oldPath)) {
                unlink($oldPath);
            }
        }
        return parent::destroy($id);
    }

}
