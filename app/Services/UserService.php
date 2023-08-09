<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserMetaRole;
use App\Models\Vendor;
use Illuminate\Support\Carbon;
use Hash;

class UserService extends ModelService
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
        'user_registered',];

    /**
     * updatable field is a field which can be filled during updating the record
     */
    protected array $updatables = ['user_nicename', 'user_login', 'user_email', 'user_pass', 'user_status'];
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
            //   $attributes['user_pass'] = Hash::make($attributes['user_pass']);
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

    public function register($attributes)
    {
        // disable registered clients
        // default
        $attributes['enable'] = false;
        $attributes['user_pass'] = Hash::make($attributes['user_pass']);
        $attributes['user_status'] = User::status_active;
        $attributes['user_registered'] = Carbon::parse(date('Y-m-d H:i:s'))->format('Y-m-d H:i:s');
        $user = $this->store($attributes);
        if ($user instanceof User) {
            // $user = $client->user()->get()->first();
            $user = $this->ignoredFind($user->ID);
            $token = $user->createToken('*');

            $data = [
                'user' => $user->toLightWeightArray(),
                'token' => $token->plainTextToken,
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
            $record->role()->create(["user_id" => $record->ID, "meta_key" => "wp_capabilities", "meta_value" => serialize(["customer" => true])]);
            $record->meta()->create(["user_id" => $record->ID, "meta_key" => "user_phone", "meta_value" => $attributes["user_phone"]]);
            $record->customer()->create(
                [
                    "user_id" => $record->ID,
                    "meta_key" => "wp_capabilities",
                    "meta_value" => serialize(["customer" => true]),
                    'username' => $attributes["user_login"],
                    'first_name' => $attributes["user_nicename"],
                    'last_name' => $attributes["user_login"],
                    'email' => $attributes["user_email"],
                    'date_last_active' => Carbon::parse(date('Y-m-d H:i:s'))->format('Y-m-d H:i:s'),
                    'date_registered' => Carbon::parse(date('Y-m-d H:i:s'))->format('Y-m-d H:i:s'),
                    'country' => "no",
                    'postcode' => $attributes["user_phone"],
                    'city' => "no",
                    'state' => "no"
                ]);

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

        if (isset($attributes["user_phone"])) {
            if ($record instanceof User) {
                if ($record->hasPhone()){

                $record->phone()->update(['meta_value' => $attributes["user_phone"]]);
                }
                else{
                    $record->meta()->create(["user_id" => $record->ID, "meta_key" => "user_phone", "meta_value" => $attributes["user_phone"]]);

                }

            }
            $record = parent::update($id, $attributes);
        }
            $data = $record->toLightWeightArray();


        return $this->ok($data, "users:update:done");
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
        if (!in_array($user->status, [User::status_unverified, User::status_suspended])) {
            throw new \Exception('users:activate:errors:bad');
        }
        $user->status = User::status_active;
        $user->saveOrFail();
        return $this->ok($id, 'users:activate:done');
    }

    /**
     * suspend user
     */
    public function suspend(int $id)
    {
        $user = $this->find($id);
        if (!in_array($user->status, [User::status_active])) {
            throw new \Exception('users:suspend:errors:base');
        }
        $user->status = User::status_suspended;
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
