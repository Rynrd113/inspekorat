<?php

namespace App\Services\Implementation;

use App\Models\PortalOpd;
use App\Repositories\Contracts\PortalOpdRepositoryInterface;
use App\Services\Contracts\PortalOpdServiceInterface;
use App\Http\Requests\StorePortalOpdRequest;
use App\Http\Requests\UpdatePortalOpdRequest;
use App\Events\PortalOpdCreated;
use App\Events\PortalOpdUpdated;
use App\Events\PortalOpdDeleted;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PortalOpdService implements PortalOpdServiceInterface
{
    protected $portalOpdRepository;

    public function __construct(PortalOpdRepositoryInterface $portalOpdRepository)
    {
        $this->portalOpdRepository = $portalOpdRepository;
    }

    public function getAllPaginated(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        return $this->portalOpdRepository->getPaginated($perPage, $filters);
    }

    public function createOpd(StorePortalOpdRequest $request): PortalOpd
    {
        return DB::transaction(function () use ($request) {
            $data = $request->validated();
            
            // Handle logo upload
            if ($request->hasFile('logo')) {
                $data['logo'] = $request->file('logo')
                    ->store('portal-opd/logos', 'public');
            }

            // Handle banner upload
            if ($request->hasFile('banner')) {
                $data['banner'] = $request->file('banner')
                    ->store('portal-opd/banners', 'public');
            }

            $data['status'] = $request->has('status');

            $portalOpd = $this->portalOpdRepository->create($data);

            // Dispatch event
            event(new PortalOpdCreated($portalOpd));

            return $portalOpd;
        });
    }

    public function updateOpd(int $id, UpdatePortalOpdRequest $request): bool
    {
        return DB::transaction(function () use ($id, $request) {
            $portalOpd = $this->portalOpdRepository->findById($id);
            
            if (!$portalOpd) {
                return false;
            }

            $data = $request->validated();

            // Handle logo upload
            if ($request->hasFile('logo')) {
                // Delete old logo if exists
                if ($portalOpd->logo) {
                    Storage::disk('public')->delete($portalOpd->logo);
                }
                
                $data['logo'] = $request->file('logo')
                    ->store('portal-opd/logos', 'public');
            }

            // Handle banner upload
            if ($request->hasFile('banner')) {
                // Delete old banner if exists
                if ($portalOpd->banner) {
                    Storage::disk('public')->delete($portalOpd->banner);
                }
                
                $data['banner'] = $request->file('banner')
                    ->store('portal-opd/banners', 'public');
            }

            $data['status'] = $request->has('status');

            $result = $this->portalOpdRepository->update($id, $data);

            // Dispatch event
            event(new PortalOpdUpdated($portalOpd->fresh()));

            return $result;
        });
    }

    public function deleteOpd(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $portalOpd = $this->portalOpdRepository->findById($id);
            
            if (!$portalOpd) {
                return false;
            }

            $result = $this->portalOpdRepository->delete($id);

            // Dispatch event
            event(new PortalOpdDeleted($portalOpd));

            return $result;
        });
    }

    public function getOpdById(int $id): ?PortalOpd
    {
        return $this->portalOpdRepository->findById($id);
    }

    public function getActiveOpds(): Collection
    {
        return $this->portalOpdRepository->getActive();
    }

    public function searchOpds(string $search): Collection
    {
        return $this->portalOpdRepository->search($search);
    }

    public function updateOpdStatus(int $id, bool $status): bool
    {
        return $this->portalOpdRepository->updateStatus($id, $status);
    }

    public function getOpdStatistics(): array
    {
        return $this->portalOpdRepository->getStatistics();
    }

    public function getOpdsByStatus(bool $status): Collection
    {
        return $this->portalOpdRepository->getByStatus($status);
    }

    public function uploadLogo(int $id, $file): bool
    {
        return DB::transaction(function () use ($id, $file) {
            $portalOpd = $this->portalOpdRepository->findById($id);
            
            if (!$portalOpd) {
                return false;
            }

            // Delete old logo if exists
            if ($portalOpd->logo) {
                Storage::disk('public')->delete($portalOpd->logo);
            }

            $logoPath = $file->store('portal-opd/logos', 'public');

            return $this->portalOpdRepository->update($id, ['logo' => $logoPath]);
        });
    }

    public function uploadBanner(int $id, $file): bool
    {
        return DB::transaction(function () use ($id, $file) {
            $portalOpd = $this->portalOpdRepository->findById($id);
            
            if (!$portalOpd) {
                return false;
            }

            // Delete old banner if exists
            if ($portalOpd->banner) {
                Storage::disk('public')->delete($portalOpd->banner);
            }

            $bannerPath = $file->store('portal-opd/banners', 'public');

            return $this->portalOpdRepository->update($id, ['banner' => $bannerPath]);
        });
    }
}
