<?php

namespace Modules\Case\Repository;

use Modules\Case\Entities\CaseFile;

class CaseRepository
{
    public function all() {
        return CaseFile:: with(['caseDegree', 'caseType', 'employee'])->get();
    }
    public function find($id) {
        return CaseFile::findOrFail($id);
    }
    public function create(array $data) {
        return CaseFile::create($data);
    }
    public function update(CaseFile $caseFile,array $data) {
        return $caseFile->update($data);
    }
    public function delete(CaseFile $caseFile) {
        return $caseFile->delete();
    }
}
