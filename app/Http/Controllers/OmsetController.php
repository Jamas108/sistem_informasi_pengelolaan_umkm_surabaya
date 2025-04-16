<?php

namespace App\Http\Controllers;

use App\Models\Omset;
use App\Models\PelakuUmkm;
use App\Models\Umkm;
use Illuminate\Http\Request;


class OmsetController extends Controller
{
    /**
     * Save omset data
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveOmset(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'umkm_id' => 'required|exists:data_umkm,id',
            'jangka_waktu' => 'required|date',
            'omset' => 'required|numeric',
            'keterangan' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            // Create new omset record
            $omset = new Omset();
            $omset->umkm_id = $request->umkm_id;
            $omset->jangka_waktu = $request->jangka_waktu;
            $omset->omset = $request->omset;
            $omset->keterangan = $request->keterangan;
            $omset->save();

            return response()->json([
                'success' => true,
                'message' => 'Data omset berhasil disimpan',
                'data' => $omset
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get omset data by ID
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getOmset($id)
    {
        try {
            $omset = Omset::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $omset
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Update omset data
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateOmset(Request $request, $id)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'umkm_id' => 'required|exists:data_umkm,id',
            'jangka_waktu' => 'required|date',
            'omset' => 'required|numeric',
            'keterangan' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            // Update omset record
            $omset = Omset::findOrFail($id);
            $omset->umkm_id = $request->umkm_id;
            $omset->jangka_waktu = $request->jangka_waktu;
            $omset->omset = $request->omset;
            $omset->keterangan = $request->keterangan;
            $omset->save();

            return response()->json([
                'success' => true,
                'message' => 'Data omset berhasil diperbarui',
                'data' => $omset
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get omset table HTML
     *
     * @param  int  $pelakuUmkmId
     * @return \Illuminate\Http\Response
     */
    public function getOmsetTable($pelakuUmkmId)
    {
        try {
            $pelakuUmkm = PelakuUmkm::with(['dataUmkm.omset'])->findOrFail($pelakuUmkmId);

            // Collect all omset data from all UMKM owned by this pelaku
            $omsetData = collect();
            foreach ($pelakuUmkm->dataUmkm as $umkm) {
                $omsetData = $omsetData->merge($umkm->omset);
            }

            // Sort by date descending
            $omsetData = $omsetData->sortByDesc('jangka_waktu')->values();

            // Generate HTML for table rows
            $html = '';
            if ($omsetData->count() > 0) {
                foreach ($omsetData as $index => $item) {
                    $badgeClass = 'badge-secondary';
                    $keteranganText = 'Stabil';

                    if ($item->keterangan == 'aktif') {
                        $badgeClass = 'badge-success';
                        $keteranganText = 'Aktif';
                    } elseif ($item->keterangan == 'tidak_aktif') {
                        $badgeClass = 'badge-danger';
                        $keteranganText = 'Tidak Aktif';
                    } elseif ($item->keterangan == 'meningkat') {
                        $badgeClass = 'badge-info';
                        $keteranganText = 'Meningkat';
                    } elseif ($item->keterangan == 'menurun') {
                        $badgeClass = 'badge-warning';
                        $keteranganText = 'Menurun';
                    } elseif ($item->keterangan == 'stabil') {
                        $badgeClass = 'badge-secondary';
                        $keteranganText = 'Stabil';
                    }

                    $html .= '<tr>';
                    $html .= '<td class="text-center">' . ($index + 1) . '</td>';
                    $html .= '<td>' . $item->dataUmkm->nama_usaha . '</td>';
                    $html .= '<td>' . Carbon::parse($item->jangka_waktu)->format('d-m-Y') . '</td>';
                    $html .= '<td>Rp. ' . number_format($item->omset, 0, ',', '.') . '</td>';
                    $html .= '<td><span class="badge ' . $badgeClass . '">' . $keteranganText . '</span></td>';
                    $html .= '<td class="text-center">';
                    $html .= '<button type="button" class="btn btn-warning btn-sm edit-omset" data-id="' . $item->id . '">';
                    $html .= '<i class="fas fa-edit"></i> Edit';
                    $html .= '</button>';
                    $html .= '</td>';
                    $html .= '</tr>';
                }
            } else {
                $html .= '<tr><td colspan="6" class="text-center">Belum ada data omset</td></tr>';
            }

            return response()->json([
                'success' => true,
                'html' => $html
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
