<?php

namespace App\Services\Implementation;

use App\Models\Wbs;
use App\Repositories\Contracts\WbsRepositoryInterface;
use App\Services\Contracts\WbsServiceInterface;
use App\Http\Requests\StoreWbsRequest;
use App\Http\Requests\UpdateWbsRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\AuditLog;

class WbsService implements WbsServiceInterface
{
    protected $wbsRepository;

    public function __construct(WbsRepositoryInterface $wbsRepository)
    {
        $this->wbsRepository = $wbsRepository;
    }

    public function getAllPaginated(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        return $this->wbsRepository->getPaginated($perPage, $filters);
    }

    public function createWbsReport(StoreWbsRequest $request): Wbs
    {
        return DB::transaction(function () use ($request) {
            $data = $request->validated();
            
            // Handle file uploads
            if ($request->hasFile('bukti_files')) {
                $uploadedFiles = [];
                foreach ($request->file('bukti_files') as $file) {
                    $uploadedFiles[] = $file->store('wbs/bukti', 'public');
                }
                $data['bukti_files'] = $uploadedFiles;
            }

            // Handle single file upload (legacy support)
            if ($request->hasFile('bukti_file')) {
                $data['bukti_file'] = $request->file('bukti_file')->store('wbs/bukti', 'public');
            }

            $data['status'] = 'pending';
            
            $wbs = $this->wbsRepository->create($data);

            // Log activity
            AuditLog::log('created', $wbs, null, $wbs->toArray());

            return $wbs;
        });
    }

    public function updateWbsReport(int $id, UpdateWbsRequest $request): bool
    {
        return DB::transaction(function () use ($id, $request) {
            $wbs = $this->wbsRepository->findById($id);
            
            if (!$wbs) {
                return false;
            }

            $oldData = $wbs->toArray();
            $data = $request->validated();

            // Handle file uploads
            if ($request->hasFile('bukti_files')) {
                // Delete old files if exists
                if ($wbs->bukti_files) {
                    foreach ($wbs->bukti_files as $file) {
                        Storage::disk('public')->delete($file);
                    }
                }
                
                $uploadedFiles = [];
                foreach ($request->file('bukti_files') as $file) {
                    $uploadedFiles[] = $file->store('wbs/bukti', 'public');
                }
                $data['bukti_files'] = $uploadedFiles;
            }

            $result = $this->wbsRepository->update($id, $data);

            // Log activity
            if ($result) {
                AuditLog::log('updated', $wbs->fresh(), $oldData, $data);
            }

            return $result;
        });
    }

    public function deleteWbsReport(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $wbs = $this->wbsRepository->findById($id);
            
            if (!$wbs) {
                return false;
            }

            // Delete associated files
            if ($wbs->bukti_files) {
                foreach ($wbs->bukti_files as $file) {
                    Storage::disk('public')->delete($file);
                }
            }

            if ($wbs->bukti_file) {
                Storage::disk('public')->delete($wbs->bukti_file);
            }

            $result = $this->wbsRepository->delete($id);

            // Log activity
            if ($result) {
                AuditLog::log('deleted', $wbs, $wbs->toArray(), null);
            }

            return $result;
        });
    }

    public function getWbsById(int $id): ?Wbs
    {
        return $this->wbsRepository->findById($id);
    }

    public function respondToReport(int $id, string $response): bool
    {
        return DB::transaction(function () use ($id, $response) {
            $wbs = $this->wbsRepository->findById($id);
            
            if (!$wbs) {
                return false;
            }

            $result = $this->wbsRepository->update($id, [
                'response' => $response,
                'responded_at' => now(),
                'status' => 'proses'
            ]);

            // Log activity
            if ($result) {
                AuditLog::log('responded', $wbs->fresh(), 
                    ['response' => $wbs->response], 
                    ['response' => $response]
                );
            }

            return $result;
        });
    }

    public function changeStatus(int $id, string $status): bool
    {
        return DB::transaction(function () use ($id, $status) {
            $wbs = $this->wbsRepository->findById($id);
            
            if (!$wbs) {
                return false;
            }

            $oldStatus = $wbs->status;
            $result = $this->wbsRepository->update($id, ['status' => $status]);

            // Log activity
            if ($result) {
                AuditLog::log('status_changed', $wbs->fresh(), 
                    ['status' => $oldStatus], 
                    ['status' => $status]
                );
            }

            return $result;
        });
    }

    public function getPendingReports(): Collection
    {
        return $this->wbsRepository->getPendingReports();
    }

    public function getStatistics(): array
    {
        return $this->wbsRepository->getStatistics();
    }
}
