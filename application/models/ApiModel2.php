<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ApiModel2 extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getDataBacaan($idbaca)
	{
		$cBlth = date('m/Y');
		$periode = date('Y-m-') . '01';
		$blnLalu1 = date('Y-m-', strtotime(date('Y-m') . " -1 month")) . '01';
		$blnLalu2 = date('Y-m-', strtotime(date('Y-m') . " -2 month")) . '01';
		$blnLalu3 = date('Y-m-', strtotime(date('Y-m') . " -3 month")) . '01';

		$sql = "SELECT pel.id as idPel,pel.no_langganan as cIdPel, pel.nama as vcNmPel,pel.alamat as vcAlamat,pel.telepon as cNoTelp,jln.nama as vcJalan,lurah.nama as vcWilayah,
		DATE_FORMAT(periode, '%m/%Y') as cBlth,gol.nama as cKdGol,baca.tanggal_baca as dTglCatat,baca.tanggal_upload as dTglUpload,baca.stand_lalu as nStLalu,
		baca.stand_ini as nStIni,baca.pakai as nPakai,3 as nPembagi,
		(SELECT pakai from pelayanan.rekening_air where periode= ? AND id_pelanggan=pel.id) as nPakaiLalu1,
		(SELECT pakai from pelayanan.rekening_air where periode= ? AND id_pelanggan=pel.id) as nPakaiLalu2,
		(SELECT pakai from pelayanan.rekening_air where periode= ? AND id_pelanggan=pel.id) as nPakaiLalu3,baca.status_baca as cKetWm,
		baca.latitude as vcLatitude,baca.longitude as vcLongitude,
		null as vcLatitudeNew,null as vcLongitudeNew,1 as lValidLokasi from 
		pelayanan.baca_meter as baca, pelayanan.pelanggan as pel, pelayanan.jalan as jln, pelayanan.kelurahan as lurah , pelayanan.golongan as gol
		WHERE baca.id_pelanggan=pel.id AND pel.id_jalan=jln.id
		AND jln.id_kelurahan=lurah.id AND pel.id_golongan=gol.id AND
		baca.periode = ? AND baca.id_pembaca = ? AND baca.tanggal_baca IS NULL";
		$query = $this->db->query($sql, array($blnLalu1, $blnLalu2, $blnLalu3, $periode, $idbaca));

		$records = array();
		foreach ($query->result_array() as $r) {
			$r['nStLalu'] = intval($r['nStLalu']);
			$r['nStIni'] = intval($r['nStIni']);
			$r['nPakai'] = intval($r['nPakai']);
			$r['nPembagi'] = intval($r['nPembagi']);
			$r['nPakaiLalu1'] = intval($r['nPakaiLalu1']);
			$r['nPakaiLalu2'] = intval($r['nPakaiLalu2']);
			$r['nPakaiLalu3'] = intval($r['nPakaiLalu3']);
			$r['lValidLokasi'] = intval($r['lValidLokasi']);
			$records[] = $r;
		}
		return $records;
	}

	public function getStatus()
	{
		$sql = "SELECT CAST(id AS NCHAR) as cKode, keterangan as cKet, 1 as status, input_angka as inputAngka FROM pelayanan.status_baca WHERE deleted_at IS NULL";
		$query = $this->db->query($sql);
		$records = array();
		foreach ($query->result_array() as $r) {
			$r['status'] = intval($r['status']);
			$r['inputAngka'] = intval($r['inputAngka']);
			$records[] = $r;
		}
		return $records;
	}

	public function getTarif()
	{
		$sql = "SELECT gol.nama as cKdGol,10 as nMin, blok_min as nPakai1 , blok_max as nPakai2, nilai as nHarga FROM pelayanan.golongan as gol, pelayanan.golongan_progresif as tarif WHERE gol.id=tarif.id AND gol.deleted_at IS NULL";
		$query = $this->db->query($sql);
		$records = array();
		foreach ($query->result_array() as $r) {
			$r['nMin'] = intval($r['nMin']);
			$r['nPakai1'] = intval($r['nPakai1']);
			$r['nPakai2'] = intval($r['nPakai2']);
			$records[] = $r;
		}
		return $records;
	}

	public function getTarif2()
	{
		$sql = "SELECT (nilai*10) as hargaMinimum, 10 as minimum, gol.nama as cKdGol,blok_min as nMin, blok_max-blok_min as nPakai1 , blok_max as nPakai2, nilai as nHarga FROM pelayanan.golongan as gol, pelayanan.golongan_progresif as tarif WHERE gol.id=tarif.id AND gol.deleted_at IS NULL";
		$query = $this->db->query($sql);
		$records = array();
		foreach ($query->result_array() as $r) {
			$r['hargaMinimum'] = (string) intval($r['hargaMinimum']);
			$r['minimum'] = intval($r['minimum']);
			$r['nMin'] = intval($r['nMin']);
			$r['nPakai1'] = intval($r['nPakai1']);
			$r['nPakai2'] = intval($r['nPakai2']);
			$r['nHarga'] = (string) intval($r['nHarga']);
			$records[] = $r;
		}
		return $records;
	}

	public function getJumBacaan($id)
	{
		$periode = date('Y-m-') . '01';
		$sql = "SELECT COUNT(*) as jum FROM pelayanan.baca_meter where periode = ? AND id_pembaca=? ";
		$query = $this->db->query($sql, array($periode, $id));
		return $query->row();
	}

	public function getJumBelumBacaan($id)
	{
		$periode = date('Y-m-') . '01';
		$sql = "SELECT COUNT(*) as jum FROM pelayanan.baca_meter where periode = ? AND id_pembaca=? AND tanggal_baca IS NULL";
		$query = $this->db->query($sql, array($periode, $id));
		return $query->row();
	}


	public function doLogin($id, $pass)

	{
		$x = [
			"status" => "Unauthorized"
		];
		$sql = "SELECT id,nama FROM pelayanan.pembaca WHERE kode=? AND kata_sandi=?";
		$query = $this->db->query($sql, array($id, $pass));
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return $x;
		}
	}
	public function updateData($data, $id)
	{
		$periode = date('Y-m-') . '01';
		$cBlth = date('m_Y/');
		$sql = "UPDATE pelayanan.baca_meter SET stand_ini = ?, stand_ini_awal = ?, pakai = ?, status_baca = ?, valid_koordinat = ?, foto = ?,  tanggal_baca = ?, tanggal_upload = NOW() WHERE periode = ? AND id_pelanggan = ? AND tanggal_baca IS NULL";
		$this->db->trans_begin();
		foreach ($data as $da) {
			$query = $this->db->query("SELECT id FROM pelayanan.pelanggan WHERE no_langganan = ? ", array($da['cIdPel']));
			$row = $query->row_array();
			$this->db->query($sql, array($da['nStIni'], $da['nStIni'], $da['nPakai'], $da['cKetWM'], $da['lValidLokasi'], base_url('images/') . $cBlth . $id . '/' . $da['txtFoto'], $da['dTglCatat'],  $periode, $row['id']));
		}
		$this->db->trans_complete();
		if ($this->db->trans_status())
			return true;
		return false;
	}

	public function sinkronData($data, $id)
	{
		$periode = date('Y-m-') . '01';
		$cBlth = date('m_Y/');
		$sql = "UPDATE pelayanan.baca_meter SET stand_ini = ?, stand_ini_awal = ? , pakai = ?, status_baca = ?, valid_koordinat = ?, foto = ?,  tanggal_baca = ?, tanggal_upload = NOW() WHERE periode = ? AND id_pelanggan = ? AND status_baca!='BACAMETER MANDIRI'";
		$this->db->trans_begin();
		foreach ($data as $da) {
			// if ($da['cBlth'] == '02/2022') {
			// 	$query = $this->db->query("SELECT id FROM pelayanan.pelanggan WHERE no_langganan = ? ", array($da['cIdPel']));
			// 	$row = $query->row_array();
			// 	$this->db->query($sql, array($da['nStIni'], $da['nStIni'], $da['nPakai'], $da['cKetWM'], $da['lValidLokasi'], base_url('images/') . $cBlth . $id . '/' . $da['txtFoto'], $da['dTglCatat'], '2022-02-01', $row['id']));
			// } else {
			// 	$query = $this->db->query("SELECT id FROM pelayanan.pelanggan WHERE no_langganan = ? ", array($da['cIdPel']));
			// 	$row = $query->row_array();
			// 	$this->db->query($sql, array($da['nStIni'], $da['nStIni'], $da['nPakai'], $da['cKetWM'], $da['lValidLokasi'], base_url('images/') . $cBlth . $id . '/' . $da['txtFoto'], $da['dTglCatat'],  $periode, $row['id']));
			// }
			$query = $this->db->query("SELECT id FROM pelayanan.pelanggan WHERE no_langganan = ? ", array($da['cIdPel']));
			$row = $query->row_array();
			$this->db->query($sql, array($da['nStIni'], $da['nStIni'], $da['nPakai'], $da['cKetWM'], $da['lValidLokasi'], base_url('images/') . $cBlth . $id . '/' . $da['txtFoto'], $da['dTglCatat'],  $periode, $row['id']));
		}

		$this->db->trans_complete();
		if ($this->db->trans_status())
			return true;
		return false;
	}
}
