<?php

namespace App\Services;

use App\Dtos\SearchQuery;
use App\Mail\templates\RegisterWelcomeMail;
use App\Models\User;
use App\Models\UserMetaRole;
use App\Models\Vendor;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Hash;

class ManagerService extends ModelService
{
    /**
     * storable field is a field which can be filled during creating the record
     */
    protected array $storables = ['user_login',
        'name',
        'user_email',
        'user_nicename',
        'phone',
        'user_pass',
        'user_status',
        'user_registered'];

    /**
     * updatable field is a field which can be filled during updating the record
     */
    protected array $updatables = ['user_nicename', 'user_login', 'user_email', 'user_pass', 'user_status', 'user_registered'];

    /**
     * searchable field is a field which can be search for from keyword parameter in search method
     */
    protected array $searchables = ['user_login', 'user_email', 'user_status'];
    /**
     *
     */
    protected UserMetaRole $role;

    public function __construct(UserMetaRole $role)
    {
        $this->role = $role;
    }

    protected array $with = ['role'];

    public function builder(): \Illuminate\Database\Eloquent\Builder
    {
        return User::query();
    }

    /**
     * prepare
     */
    protected function prepare(string $operation, array $attributes): array
    {
        if (isset($attributes['user_pass'])) {
               $attributes['user_pass'] = Hash::make($attributes['user_pass']);
        }
        if ($operation === 'store') {
            if (!isset($attributes['user_status'])) {
                $attributes['user_status'] = Vendor::status_active;
            }
            if (!isset($attributes['language'])) {
                $attributes['language'] = 'en';
            }
        }
        return parent::prepare($operation, $attributes);
    }

    public function create($attributes)
    {

        // disable registered clients
        // default
        $attributes['enable'] = false;
        $attributes['user_status'] = User::status_active;
        $attributes['user_registered'] = Carbon::parse(date('Y-m-d H:i:s'))->format('Y-m-d H:i:s');
        $user = $this->store($attributes);
        // user_attributes ==> user_login, user_nicename, user_email, user_phone
        if ($user instanceof User) {
            // $user = $client->user()->get()->first();
            $user = $this->ignoredFind($user->ID);
            // send mail to registered user
            Mail::to($user->user_email)->send(new RegisterWelcomeMail($user));
            $data = [
                'user' => $user->toLightWeightArray(),
                'message' => 'Welcome email message sent to user'
            ];
            if ($user instanceof User) {
                return $this->ok($data, 'clients:register:step1:done');
            }
        }
        throw new \Exception('clients:register:step1:errors:failed');


    }


    /**
     * create a new user
     */
    public function store(array $attributes): User
    {
        $record = parent::store($attributes);
        // TODO: sites attribute value
        if ($record instanceof User) {
            $record->role()->create(["user_id" => $record->ID, "meta_key" => "wp_capabilities", "meta_value" => serialize(["manager" => true])]);
            $record->meta()->create(["user_id" => $record->ID, "meta_key" => "user_phone", "meta_value" => $attributes["user_phone"]]);
        }
        return $record;
    }

    /**
     *update user
     *
     */
    public function update2($id, array $attributes)
    {
        $fields = $this->prepare('update', $attributes);
        $record = $this->find($id);

        if ($attributes["user_phone"]) {
            if ($record instanceof User) {
                $record->phone()->update(['meta_value' => $attributes["user_phone"]]);

            }
            $record = parent::update($id, $attributes);
        }

        return $this->ok($record, "users:update:done");
    }

    public function getUserByEmail($email)
    {
        $result = $this->search(SearchQuery::fromJson(
            ["offset" => "0",
                "limit" => "23",
                "sort" => [
                    "column" => "name",
                    "order" => "asc"],
                "fields" => [
                    "user_email" => [
                        "value" => $email
                    ]
                ]
            ]));

        return $result->data[0];
    }

    /**
     * delete inner
     */

    public function destroy($id)
    {
        $record = $this->find($id);
        if ($record instanceof Vendor) {
            $addresses = $record->addresses()->get()->all();
            foreach ($addresses as $address) {
                if ($address instanceof \App\Models\Address) {
                    $address->delete();
                }
            }
        }
        return parent::destroy($id);
    }

    /**
     * activate user
     */
    public function activate(int $id)
    {
        $user = $this->find($id);
        if (!in_array($user->user_status, [User::status_suspended])) {
            throw new \Exception('users:activate:errors:bad');
        }
        $user->user_status = User::status_active;
        $user->saveOrFail();
        return $this->ok($id, 'users:activate:done');
    }

    /**
     * suspend user
     */
    public function suspend(int $id)
    {
        $user = $this->find($id);
        if (!in_array($user->user_status, [User::status_active])) {
            throw new \Exception('users:suspend:errors:base');
        }
        $user->user_status = User::status_suspended;
        $user->saveOrFail();
        return $this->ok($id, 'users:suspend:done');
    }

    /**
     *
     */
    public function ignoredFind($id): User
    {
        $qb = $this->builder()->withoutGlobalScope('accessDB');
        if ($id == null) {
            throw new \Exception('records:find:errors:not_found');
        } else if (is_array($id)) {
            if (!count($id)) {
                throw new \Exception('records:find:errors:not_found');
            }
            foreach ($id as $k => $value) {
                $qb = $qb->where($k, '=', $value);
            }
        } else {
            $qb = $qb->where('id', '=', $id);
        }
        $qb = $qb->first();
        if (!$qb instanceof User) {
            throw new \Exception('records:find:errors:not_found');
        }
        return $qb;
    }
}
