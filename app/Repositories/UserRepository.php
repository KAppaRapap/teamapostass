<?php

namespace App\Repositories;

use Kreait\Firebase\Contract\Firestore;

class UserRepository
{
    protected $firestore;
    protected $collectionName = 'users';

    public function __construct(Firestore $firestore)
    {
        $this->firestore = $firestore;
    }

    public function create(array $data)
    {
        $document = $this->firestore->database()->collection($this->collectionName)->newDocument();
        $data['id'] = $document->id();
        $document->set($data);
        return $data;
    }

    public function find(string $id)
    {
        $document = $this->firestore->database()->collection($this->collectionName)->document($id)->snapshot();

        if ($document->exists()) {
            return $document->data();
        }

        return null;
    }

    public function update(string $id, array $data)
    {
        $document = $this->firestore->database()->collection($this->collectionName)->document($id);
        $document->set($data, ['merge' => true]);

        return $this->find($id);
    }

    public function delete(string $id)
    {
        $this->firestore->database()->collection($this->collectionName)->document($id)->delete();
    }
} 