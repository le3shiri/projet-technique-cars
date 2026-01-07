<?php

namespace App\Services;

use App\Models\Car;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class CarService
{
    /**
     * Récupérer les voitures avec recherche, filtre et pagination
     *
     * @param string|null $search Recherche par nom de modèle (partial)
     * @param int|null $modelId Filtre par ID de modèle (exact)
     * @param int $perPage Pagination (défaut 4)
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getCars(?string $search = null, ?int $modelId = null, int $perPage = 4)
    {
        $query = Car::with('model'); // Eager load model

        // Recherche par model (Brand or Name)
        if ($search) {
            $query->whereHas('model', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('brand', 'like', '%' . $search . '%');
            });
        }

        // Filtre par model (Specific Model ID)
        if ($modelId) {
            $query->where('model_id', $modelId);
        }

        // Pagination : 4 par page
        return $query->paginate($perPage);
    }

    /**
     * Ajouter une voiture avec upload image
     *
     * @param array $data
     * @return Car
     */
    public function createCar(array $data): Car
    {
        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            $data['image'] = $this->uploadImage($data['image']);
        }

        return Car::create($data);
    }

    /**
     * Mettre à jour une voiture
     *
     * @param Car $car
     * @param array $data
     * @return Car
     */
    public function updateCar(Car $car, array $data): Car
    {
        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            // Delete old image if exists
            if ($car->image) {
                $this->deleteImage($car->image);
            }
            $data['image'] = $this->uploadImage($data['image']);
        }

        $car->update($data);

        return $car;
    }

    /**
     * Supprimer une voiture
     *
     * @param Car $car
     * @return bool|null
     */
    public function deleteCar(Car $car): ?bool
    {
        if ($car->image) {
            $this->deleteImage($car->image);
        }

        return $car->delete();
    }

    /**
     * Handle Image Upload
     * 
     * @param UploadedFile $file
     * @return string Path
     */
    private function uploadImage(UploadedFile $file): string
    {
        // Store in 'public/images' (storage/app/public/images) and return relative path 'images/filename'
        // Or store directly. Let's use standard storage 'public' disk.
        // Assuming 'images' directory inside public disk.
        // The accessible URL would be /storage/images/filename.
        // However, the seeder used 'images/car1.jpg' which implies root of public usually.
        // I will stick to Storage facade, which is safer.
        
        $path = $file->store('images', 'public');
        return $path;
    }

    /**
     * Delete Image
     * 
     * @param string $path
     */
    private function deleteImage(string $path): void
    {
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
