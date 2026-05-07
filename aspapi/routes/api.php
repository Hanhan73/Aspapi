Route::get('/cities/{province}', function (App\Models\Province $province) {
    return $province->cities()->orderBy('name')->get(['id', 'name']);
});