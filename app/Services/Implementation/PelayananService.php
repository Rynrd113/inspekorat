<?php

namespace App\Services\Implementation;

use App\Models\Pelayanan;
use App\Repositories\Contracts\PelayananRepositoryInterface;
use App\Services\Contracts\PelayananServiceInterface;
use App\Http\Requests\StorePelayananRequest;
use App\Http\Requests\UpdatePelayananRequest;
use App\Events\PelayananCreated;
use App\Events\PelayananUpdated;
use App\Events\PelayananDeleted;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PelayananService implements PelayananServiceInterface
{
    protected $pelayananRepository;

    public function __construct(PelayananRepositoryInterface $pelayananRepository)
    {
        $this->pelayananRepository = $pelayananRepository;
    }

    public function getAllPaginated(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        return $this->pelayananRepository->getPaginated($perPage, $filters);
    }

    public function createPelayanan(StorePelayananRequest $request): Pelayanan
    {
        return DB::transaction(function () use ($request) {
            $data = $request->validated();
            
            // Map form field names to model column names
            if (isset($data['nama_layanan'])) {
                $data['nama'] = $data['nama_layanan'];
                unset($data['nama_layanan']);
            }

            if (isset($data['waktu_pelayanan'])) {
                $data['waktu_penyelesaian'] = $data['waktu_pelayanan'];
                unset($data['waktu_pelayanan']);
            }

            // Handle file upload
            if ($request->hasFile('file_formulir')) {
                $data['file_formulir'] = $request->file('file_formulir')
                    ->store('pelayanan/formulir', 'public');
            }

            $data['created_by'] = auth()->id();
            $data['status'] = $request->has('status');

            $pelayanan = $this->pelayananRepository->create($data);

            return $pelayanan;
        });
    }

    public function updatePelayanan(int $id, UpdatePelayananRequest $request): bool
    {
        return DB::transaction(function () use ($id, $request) {
            $pelayanan = $this->pelayananRepository->findById($id);
            
            if (!$pelayanan) {
                return false;
            }

            $data = $request->validated();

            // Map form field names to model column names
            if (isset($data['nama_layanan'])) {
                $data['nama'] = $data['nama_layanan'];
                unset($data['nama_layanan']);
            }

            if (isset($data['waktu_pelayanan'])) {
                $data['waktu_penyelesaian'] = $data['waktu_pelayanan'];
                unset($data['waktu_pelayanan']);
            }

            // Handle file upload
            if ($request->hasFile('file_formulir')) {
                // Delete old file if exists
                if ($pelayanan->file_formulir) {
                    Storage::disk('public')->delete($pelayanan->file_formulir);
                }
                
                $data['file_formulir'] = $request->file('file_formulir')
                    ->store('pelayanan/formulir', 'public');
            }

            $data['updated_by'] = auth()->id();
            $data['status'] = $request->has('status');

            $result = $this->pelayananRepository->update($id, $data);

            return $result;
        });
    }

    public function deletePelayanan(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $pelayanan = $this->pelayananRepository->findById($id);
            
            if (!$pelayanan) {
                return false;
            }

            // Delete associated file if exists
            if ($pelayanan->file_formulir) {
                Storage::disk('public')->delete($pelayanan->file_formulir);
            }

            $result = $this->pelayananRepository->delete($id);

            // Dispatch event
            event(new PelayananDeleted($pelayanan));

            return $result;
        });
    }

    public function getPublicPelayanan(): Collection
    {
        return $this->pelayananRepository->getActive();
    }

    public function searchPelayanan(string $search): SupportCollection
    {
        return $this->pelayananRepository->getPaginated(100, ['search' => $search])
                                        ->getCollection();
    }
}
