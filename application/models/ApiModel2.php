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

		$sql = "SELECT pel.id as idPel,pel.no_langganan as cIdPel, pel.nama as vcNmPel,pel.alamat as vcAlamat, DATE_FORMAT(pel.tanggal_ganti_water_meter,'%d-%m-%Y') as dTglGantiMeter,pel.telepon as cNoTelp,jln.nama as vcJalan,lurah.nama as vcWilayah,
		DATE_FORMAT(periode, '%m/%Y') as cBlth, gol.nama as cKdGol, gol.keterangan as cKetGol,baca.tanggal_baca as dTglCatat,baca.tanggal_upload as dTglUpload,baca.stand_lalu as nStLalu,
		baca.stand_ini as nStIni,baca.pakai as nPakai,3 as nPembagi,
		(SELECT pakai from pelayanan.rekening_air where periode= ? AND id_pelanggan=pel.id) as nPakaiLalu1,
		(SELECT pakai from pelayanan.rekening_air where periode= ? AND id_pelanggan=pel.id) as nPakaiLalu2,
		(SELECT pakai from pelayanan.rekening_air where periode= ? AND id_pelanggan=pel.id) as nPakaiLalu3,
		baca.status_baca as cKetWm,
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

	public function getDataBacaanWithRekening($idbaca)
	{
		$periode = date('Y-m-') . '01';
		$blnLalu1 = date('Y-m-', strtotime(date('Y-m') . " -1 month")) . '01';
		$blnLalu2 = date('Y-m-', strtotime(date('Y-m') . " -2 month")) . '01';
		$blnLalu3 = date('Y-m-', strtotime(date('Y-m') . " -3 month")) . '01';

		$sql = "SELECT pel.id as idPel,pel.no_langganan as cIdPel, pel.nama as vcNmPel,pel.alamat as vcAlamat, DATE_FORMAT(pel.tanggal_ganti_water_meter,'%d-%m-%Y') as dTglGantiMeter,pel.telepon as cNoTelp,jln.nama as vcJalan,lurah.nama as vcWilayah,
		DATE_FORMAT(periode, '%m/%Y') as cBlth, gol.nama as cKdGol, gol.keterangan as cKetGol,baca.tanggal_baca as dTglCatat,baca.tanggal_upload as dTglUpload,baca.stand_lalu as nStLalu,
		baca.stand_ini as nStIni,baca.pakai as nPakai,3 as nPembagi,
		(SELECT pakai from pelayanan.rekening_air where periode= ? AND id_pelanggan=pel.id) as nPakaiLalu1,
		(SELECT pakai from pelayanan.rekening_air where periode= ? AND id_pelanggan=pel.id) as nPakaiLalu2,
		(SELECT pakai from pelayanan.rekening_air where periode= ? AND id_pelanggan=pel.id) as nPakaiLalu3,
		baca.status_baca as cKetWm,
		baca.latitude as vcLatitude,baca.longitude as vcLongitude,
		null as vcLatitudeNew,null as vcLongitudeNew, baca.valid_koordinat as lValidLokasi from 
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
			$r['rekening'] = $this->getRekening($r['idPel']);
			$records[] = $r;
		}
		return $records;
	}

	public function getDataBacaanWithRekeningV2($idbaca)
	{
		//aktif saat ini
		$periode = date('Y-m-') . '01';
		$blnLalu1 = date('Y-m-', strtotime(date('Y-m') . " -1 month")) . '01';
		$blnLalu2 = date('Y-m-', strtotime(date('Y-m') . " -2 month")) . '01';
		$blnLalu3 = date('Y-m-', strtotime(date('Y-m') . " -3 month")) . '01';

		$sql = "SELECT pel.id as idPel,pel.no_langganan as cIdPel, pel.nama as vcNmPel,pel.alamat as vcAlamat, DATE_FORMAT(pel.tanggal_ganti_water_meter,'%d-%m-%Y') as dTglGantiMeter,pel.telepon as cNoTelp,jln.nama as vcJalan,lurah.nama as vcWilayah,
		DATE_FORMAT(periode, '%m/%Y') as cBlth, gol.nama as cKdGol, gol.keterangan as cKetGol,baca.tanggal_baca as dTglCatat,baca.tanggal_upload as dTglUpload,baca.stand_lalu as nStLalu,
		baca.stand_ini as nStIni,baca.pakai as nPakai,3 as nPembagi,
		(SELECT pakai from pelayanan.rekening_air where periode= ? AND id_pelanggan=pel.id) as nPakaiLalu1,
		(SELECT pakai from pelayanan.rekening_air where periode= ? AND id_pelanggan=pel.id) as nPakaiLalu2,
		(SELECT pakai from pelayanan.rekening_air where periode= ? AND id_pelanggan=pel.id) as nPakaiLalu3,
	
		baca.status_baca as cKetWm,
		baca.latitude as vcLatitude,baca.longitude as vcLongitude,baca.foto as txtFoto,
		null as vcLatitudeNew,null as vcLongitudeNew, baca.valid_koordinat as lValidLokasi,
		wm.id waterMeterId, wm.merk waterMeterMerk, pel.no_body_water_meter waterMeterNomor from 
		pelayanan.baca_meter as baca 
		LEFT JOIN pelayanan.pelanggan as pel ON baca.id_pelanggan=pel.id
		LEFT JOIN pelayanan.jalan as jln ON pel.id_jalan=jln.id
		LEFT JOIN pelayanan.kelurahan as lurah ON jln.id_kelurahan=lurah.id
		LEFT JOIN pelayanan.golongan as gol ON pel.id_golongan=gol.id
		LEFT JOIN pelayanan.water_meter wm ON wm.id = pel.id_water_meter
		WHERE 
		baca.periode = ? AND baca.id_pembaca = ?";
		$query = $this->db->query($sql, array($blnLalu1, $blnLalu2, $blnLalu3,  $periode, $idbaca));

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
			$r['rekening'] = $this->getRekening($r['idPel']);
			$r['status_baca'] = $this->getStatusBaca($blnLalu1, $r['idPel']);
			$records[] = $r;
		}
		return $records;
	}


	public function getRekening($id)
	{
		$sql = "SELECT biaya_denda as denda,rekening.periode,rekening.harga_air+rekening.biaya_retribusi+rekening.biaya_jasa_lingkungan+rekening.biaya_pemeliharaan+rekening.biaya_administrasi+rekening.biaya_materai+rekening.biaya_ppn-rekening.diskon as total from pelayanan.rekening_air as rekening, pelayanan.golongan as gol where id_pelanggan=? AND kasir is NULL AND waktu_bayar IS NULL AND rekening.id_golongan=gol.id";
		$query = $this->db->query($sql, array($id));
		$records = array();
		foreach ($query->result_array() as $r) {
			$r['total'] = intval($r['total']);
			$r['denda'] = intval($r['denda']);
			$records[] = $r;
		}
		return $records;
	}

	public function getStatusBaca($periode, $id)
	{
		$sql = "SELECT status_baca from pelayanan.baca_meter where periode<=? and id_pelanggan=? order by periode desc limit 3";
		$query = $this->db->query($sql, array($periode, $id));
		$records = array();
		$records = $query->result_array();
		return $records;
	}

	public function getWaterMeter()
	{
		$sql = "SELECT id, merk from pelayanan.water_meter";
		$query = $this->db->query($sql);
		$records = array();
		$records = $query->result_array();
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
		$sql = "SELECT gol.nama as cKdGol,10 as nMin, blok_min as nPakai1 , blok_max as nPakai2, nilai as nHarga FROM pelayanan.golongan as gol, pelayanan.golongan_progresif as tarif WHERE gol.id=tarif.id AND gol.status=1 AND gol.deleted_at IS NULL";
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


	public function getTarifDenda()
	{
		$sql = "SELECT denda.nama_golongan as cKdGol, nilai as nHarga FROM  pelayanan.tarif_denda_detail as denda ";
		$query = $this->db->query($sql);
		// $records = array();
		// foreach ($query->result_array() as $r) {
		// 	$r['nMin'] = intval($r['nMin']);
		// 	$r['nPakai1'] = intval($r['nPakai1']);
		// 	$r['nPakai2'] = intval($r['nPakai2']);
		// 	$records[] = $r;
		// }
		return $query->result_array();
	}

	public function getTarif2()
	{
		$sql = "SELECT (nilai*10) as hargaMinimum, 10 as minimum, gol.nama as cKdGol,blok_min as nMin, blok_max-blok_min as nPakai1 , blok_max as nPakai2, nilai as nHarga FROM pelayanan.golongan as gol, pelayanan.golongan_progresif as tarif WHERE gol.id=tarif.id AND gol.status=1 AND gol.deleted_at IS NULL";
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
		return $query->row()->jum;
	}

	public function getRumahKonci($id, $status)
	{
		$periode = date('Y-m-') . '01';
		$status = strtoupper(str_replace('%20', ' ', $status));
		$sql = "SELECT p.no_langganan FROM pelayanan.baca_meter b JOIN pelayanan.pelanggan p ON b.id_pelanggan=p.id where periode = ? AND b.id_pembaca=? AND b.status_baca=?";
		$query = $this->db->query($sql, array($periode, $id, $status));
		return $query->result_array();
	}

	public function getJumBelumBacaan($id)
	{
		$periode = date('Y-m-') . '01';
		$sql = "SELECT COUNT(*) as jum FROM pelayanan.baca_meter where periode = ? AND id_pembaca=? AND tanggal_baca IS NULL";
		$query = $this->db->query($sql, array($periode, $id));
		return $query->row()->jum;
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
		$sql = "UPDATE pelayanan.baca_meter SET stand_ini = ?, stand_ini_awal = ?, pakai = ?, status_baca = ?, valid_koordinat = ?, foto = ?,  tanggal_baca = ?, tanggal_upload = NOW() WHERE periode = ? AND id_pelanggan = ? AND status_baca!='BACAMETER MANDIRI";
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


	public function updateDataSurvey($data, $id)
	{
		$periode = date('Y-m-') . '01';
		$cBlth = date('m_Y/');
		$sql = "UPDATE pelayanan.baca_meter SET indikasi_kelainan = ?, foto_survey = ?, catatan_survey = ?, stand_ini = ?, stand_ini_awal = ?, pakai = ?, status_baca = ?, valid_koordinat = ?, foto = ?,  tanggal_baca = ?, tanggal_upload = NOW() WHERE periode = ? AND id_pelanggan = ? AND tanggal_baca IS NULL";
		$this->db->trans_begin();
		foreach ($data as $da) {
			$query = $this->db->query("SELECT id FROM pelayanan.pelanggan WHERE no_langganan = ? ", array($da['cIdPel']));
			$row = $query->row_array();
			$name_foto_survey = null;
			if ($da['txtFotoSurvey'] != null) {
				$name_foto_survey = base_url('images/') . $cBlth . $id . '/' . $da['txtFotoSurvey'];
			}
			$this->db->query($sql, array($da['cIndikasiKelainan'], $name_foto_survey, $da['cKetSurvey'], $da['nStIni'], $da['nStIni'], $da['nPakai'], $da['cKetWM'], $da['lValidLokasi'], base_url('images/') . $cBlth . $id . '/' . $da['txtFoto'], $da['dTglCatat'],  $periode, $row['id']));
		}
		$this->db->trans_complete();
		if ($this->db->trans_status())
			return true;
		return false;
	}

	public function sinkronDataSurvey($data, $id)
	{
		$periode = date('Y-m-') . '01';
		$cBlth = date('m_Y/');
		$sql = "UPDATE pelayanan.baca_meter SET indikasi_kelainan = ?, foto_survey = ?, catatan_survey = ?, stand_ini = ?, stand_ini_awal = ? , pakai = ?, status_baca = ?, valid_koordinat = ?, foto = ?,  tanggal_baca = ?, tanggal_upload = NOW() WHERE periode = ? AND id_pelanggan = ? AND status_baca!='BACAMETER MANDIRI'";
		$this->db->trans_begin();
		foreach ($data as $da) {
			$query = $this->db->query("SELECT id FROM pelayanan.pelanggan WHERE no_langganan = ? ", array($da['cIdPel']));
			$row = $query->row_array();
			$name_foto_survey = null;
			if ($da['txtFotoSurvey'] != null) {
				$name_foto_survey = base_url('images/') . $cBlth . $id . '/' . $da['txtFotoSurvey'];
			}
			$this->db->query($sql, array($da['cIndikasiKelainan'], $name_foto_survey, $da['cKetSurvey'], $da['nStIni'], $da['nStIni'], $da['nPakai'], $da['cKetWM'], $da['lValidLokasi'], base_url('images/') . $cBlth . $id . '/' . $da['txtFoto'], $da['dTglCatat'],  $periode, $row['id']));
		}

		$this->db->trans_complete();
		if ($this->db->trans_status())
			return true;
		return false;
	}
	public function autonoumberAduan()
	{
		$this->db->limit(1);
		$this->db->order_by('id', 'DESC');
		$qry = $this->db->get('pengaduan.tt_aduan');
		$rs = $qry->result();
		$nr = $qry->num_rows();

		$v_blth        = date("mY");
		$v_blthlast    = date("mY", strtotime($rs[0]->dTglSelesaiInput));

		$v_bln        = date("m");
		$v_a_bln    = array('I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII');
		$v_blnf        = $v_a_bln[$v_bln - 1];

		$v_thn        = date("Y");

		if ($nr <= 0) {
			$v_kdaduan = "00001/ADUAN/PTAM/I/" . $v_thn;
		} else {
			if ($v_blth != $v_blthlast) {
				$v_a = "00001";
			} else {
				$v_a = sprintf("%05s", substr($rs[0]->cKdAduan, 0, 5) + 1);
			}
			$v_kdaduan    = $v_a . "/ADUAN/PTAM/" . $v_blnf . "/" . $v_thn;
		}
		return $v_kdaduan;
	}

	public function getWilayahFromIdPel($id)
	{
		$this->db->limit(1);
		$this->db->select('vw_pelanggan.id_kecamatan AS id');
		$this->db->from('pelayanan.vw_pelanggan');
		$this->db->where('vw_pelanggan.no_langganan', $id);
		$query = $this->db->get();
		return $query->result()[0]->id;
	}

	public function updateDataV3($data, $id)
	{
		$periode = date('Y-m-') . '01';
		$cBlth = date('m_Y/');
		$this->db->trans_begin();
		foreach ($data as $da) {
			// return substr($da['txtFoto'], -27);
			$foto = 'http://10.10.222.236:8084/images/' . $cBlth . $id . '/' . $da['txtFoto'];
			$query = $this->db->query("SELECT id FROM pelayanan.pelanggan WHERE no_langganan = ? ", array($da['cIdPel']));
			$row = $query->row_array();
			$name_foto_survey = null;
			if ($da['txtFotoSurvey'] != null) {
				$name_foto_survey = 'http://10.10.222.236:8084/images/' . $cBlth . $id . '/' . $da['txtFotoSurvey'];
			}

			if ($da['cKetWM'] == null) {
				$sql = "UPDATE pelayanan.baca_meter SET new_latitude = ?, new_longitude = ?,telepon = ?, alamat = ?,indikasi_kelainan = ?, foto_survey = ?, catatan_survey = ?, stand_ini = ?, stand_ini_awal = ?, pakai = ?, valid_koordinat = ?, foto = ?,  tanggal_baca = ?, tanggal_upload = NOW() WHERE periode = ? AND id_pelanggan = ? AND (status_baca != 'BACAMETER MANDIRI' OR status_baca IS NULL)";
				$this->db->query($sql, array(($da['vcLatitudeNew'] != 'null') ? $da['vcLatitudeNew'] : null, ($da['vcLongitudeNew'] != 'null') ? $da['vcLongitudeNew'] : null, ($da['telepon'] != 'null') ? $da['telepon'] : null, ($da['alamat'] != 'null') ? $da['alamat'] : null, $da['cIndikasiKelainan'], $name_foto_survey, $da['cKetSurvey'], $da['nStIni'], $da['nStIni'], $da['nPakai'], $da['lValidLokasi'], $foto, $da['dTglCatat'],  $periode, $row['id']));

				// $sql_pelanggan = "UPDATE pelayanan.pelanggan SET telepon = ? WHERE id = ?";
				// $this->db->query($sql_pelanggan, array($da['telepon'], $row['id']));
			} else {
				$sql = "UPDATE pelayanan.baca_meter SET new_latitude = ?, new_longitude = ?,telepon = ?, alamat = ?,indikasi_kelainan = ?, foto_survey = ?, catatan_survey = ?, stand_ini = ?, stand_ini_awal = ?, pakai = ?, status_baca = ?, valid_koordinat = ?, foto = ?,  tanggal_baca = ?, tanggal_upload = NOW() WHERE periode = ? AND id_pelanggan = ? AND (status_baca != 'BACAMETER MANDIRI' OR status_baca IS NULL)";
				$this->db->query($sql, array(($da['vcLatitudeNew'] != 'null') ? $da['vcLatitudeNew'] : null, ($da['vcLongitudeNew'] != 'null') ? $da['vcLongitudeNew'] : null, ($da['telepon'] != 'null') ? $da['telepon'] : null, ($da['alamat'] != 'null') ? $da['alamat'] : null, $da['cIndikasiKelainan'], $name_foto_survey, $da['cKetSurvey'], $da['nStIni'], $da['nStIni'], $da['nPakai'], $da['cKetWM'], $da['lValidLokasi'], $foto, $da['dTglCatat'],  $periode, $row['id']));

				// $sql_pelanggan = "UPDATE pelayanan.pelanggan SET telepon = ? WHERE id = ?";
				// $this->db->query($sql_pelanggan, array($da['telepon'], $row['id']));
			}
		}
		$this->db->trans_complete();
		if ($this->db->trans_status())
			return true;
		return false;
	}

	public function getKeteranganPerubahan($noHpPelanggan, $noHpUpload, $wmPelanggan, $wmUpload)
	{
		$keterangan = [];

		if ($noHpPelanggan !== $noHpUpload) {
			$keterangan[] = 'No Hp';
		}

		if ($wmPelanggan !== $wmUpload) {
			$keterangan[] = 'Water Meter';
		}

		if (empty($keterangan)) {
			return '-';
		}

		return 'Aplikasi Bacameter : Update ' . implode(' dan ', $keterangan);
	}

	public function updateDataV4($data, $id)
	{
		$periode = date('Y-m-') . '01';
		$cBlth = date('m_Y/');
		$this->db->trans_begin();
		foreach ($data as $da) {
			// return substr($da['txtFoto'], -27);
			$foto = 'http://10.10.222.236:8084/images/' . $cBlth . $id . '/' . $da['txtFoto'];
			$query = $this->db->query("SELECT id, nama, alamat, id_water_meter, telepon FROM pelayanan.pelanggan WHERE no_langganan = ? ", array($da['cIdPel']));
			$row = $query->row_array();
			$name_foto_survey = null;
			if ($da['txtFotoSurvey'] != null) {
				$name_foto_survey = 'http://10.10.222.236:8084/images/' . $cBlth . $id . '/' . $da['txtFotoSurvey'];
			}

			if ($da['cKetWM'] == null) {
				$sql = "UPDATE pelayanan.baca_meter SET new_latitude = ?, new_longitude = ?,telepon = ?, alamat = ?,indikasi_kelainan = ?, foto_survey = ?, catatan_survey = ?, stand_ini = ?, stand_ini_awal = ?, pakai = ?, valid_koordinat = ?, foto = ?,  tanggal_baca = ?, tanggal_upload = NOW() WHERE periode = ? AND id_pelanggan = ? AND (status_baca != 'BACAMETER MANDIRI' OR status_baca IS NULL)";
				$this->db->query($sql, array(($da['vcLatitudeNew'] != 'null') ? $da['vcLatitudeNew'] : null, ($da['vcLongitudeNew'] != 'null') ? $da['vcLongitudeNew'] : null, ($da['telepon'] != 'null') ? $da['telepon'] : null, ($da['alamat'] != 'null') ? $da['alamat'] : null, $da['cIndikasiKelainan'], $name_foto_survey, $da['cKetSurvey'], $da['nStIni'], $da['nStIni'], $da['nPakai'], $da['lValidLokasi'], $foto, $da['dTglCatat'],  $periode, $row['id']));

				if ($row['telepon'] != $da['telepon'] || $da['waterMeterId'] != $row['id_water_meter']) {
					$data_awal = "Nama : " . $row['nama'] .
						"<br>Alamat : " . $row['alamat'] .
						"<br>No Hp : " . $row['telepon'] .
						"<br>Water Meter : " . $row['id_water_meter'];

					$data_akhir = "Nama : " . $da['vcNmPel'] .
						"<br>Alamat : " . $da['alamat'] .
						"<br>No Hp : " . $da['telepon'] .
						"<br>Water Meter : " . $da['waterMeterId'];

					$data_log_pelanggan = [
						'id_transaksi' => null,
						'id_pelanggan' => $row['id'],
						'data_awal'    => $data_awal,
						'data_akhir'   => $data_akhir,
						'keterangan'   => $this->getKeteranganPerubahan($row['telepon'], $da['cNoTelp'], $row['id_water_meter'], $da['waterMeterId']),
						'aksi'         => "Ganti Profil",
						'operator'     => $id,
						'created_at'   => date('Y-m-d H:i:s'),
						'updated_at'   => date('Y-m-d H:i:s')
					];

					$this->db->insert('pelayanan.log_pelanggan', $data_log_pelanggan);
					$sql_pelanggan = "UPDATE pelayanan.pelanggan SET telepon = ?, id_water_meter = ? WHERE id = ?";
					$this->db->query($sql_pelanggan, array($da['telepon'], $da['waterMeterId'], $row['id']));
				}
			} else {
				$sql = "UPDATE pelayanan.baca_meter SET new_latitude = ?, new_longitude = ?,telepon = ?, alamat = ?,indikasi_kelainan = ?, foto_survey = ?, catatan_survey = ?, stand_ini = ?, stand_ini_awal = ?, pakai = ?, status_baca = ?, valid_koordinat = ?, foto = ?,  tanggal_baca = ?, tanggal_upload = NOW() WHERE periode = ? AND id_pelanggan = ? AND (status_baca != 'BACAMETER MANDIRI' OR status_baca IS NULL)";
				$this->db->query($sql, array(($da['vcLatitudeNew'] != 'null') ? $da['vcLatitudeNew'] : null, ($da['vcLongitudeNew'] != 'null') ? $da['vcLongitudeNew'] : null, ($da['telepon'] != 'null') ? $da['telepon'] : null, ($da['alamat'] != 'null') ? $da['alamat'] : null, $da['cIndikasiKelainan'], $name_foto_survey, $da['cKetSurvey'], $da['nStIni'], $da['nStIni'], $da['nPakai'], $da['cKetWM'], $da['lValidLokasi'], $foto, $da['dTglCatat'],  $periode, $row['id']));

				if ($row['telepon'] != $da['telepon'] || $da['waterMeterId'] != $row['id_water_meter']) {
					$data_awal = "Nama : " . $row['nama'] .
						"<br>Alamat : " . $row['alamat'] .
						"<br>No Hp : " . $row['telepon'] .
						"<br>Water Meter : " . $row['id_water_meter'];

					$data_akhir = "Nama : " . $da['vcNmPel'] .
						"<br>Alamat : " . $da['alamat'] .
						"<br>No Hp : " . $da['telepon'] .
						"<br>Water Meter : " . $da['waterMeterId'];

					$data_log_pelanggan = [
						'id_transaksi' => null,
						'id_pelanggan' => $row['id'],
						'data_awal'    => $data_awal,
						'data_akhir'   => $data_akhir,
						'keterangan'   => $this->getKeteranganPerubahan($row['telepon'], $da['cNoTelp'], $row['id_water_meter'], $da['waterMeterId']),
						'aksi'         => "Ganti Profil",
						'operator'     => $id,
						'created_at'   => date('Y-m-d H:i:s'),
						'updated_at'   => date('Y-m-d H:i:s')
					];

					$this->db->insert('pelayanan.log_pelanggan', $data_log_pelanggan);
					$sql_pelanggan = "UPDATE pelayanan.pelanggan SET telepon = ?, id_water_meter = ? WHERE id = ?";
					$this->db->query($sql_pelanggan, array($da['telepon'], $da['waterMeterId'], $row['id']));
				}
			}
		}
		$this->db->trans_complete();
		if ($this->db->trans_status())
			return true;
		return false;
	}



	public function addDataPengaduan($data = '')
	{
		$this->db->insert('pengaduan.tt_aduan', $data);
	}

	public function sinkronDataV3($data, $id)
	{
		$periode = date('Y-m-') . '01';
		$cBlth = date('m_Y/');
		$sql = "UPDATE pelayanan.baca_meter SET new_latitude = ?, new_longitude = ?, telepon = ?, alamat = ?, indikasi_kelainan = ?, foto_survey = ?, catatan_survey = ?, stand_ini = ?, stand_ini_awal = ? , pakai = ?, status_baca = ?, valid_koordinat = ?, foto = ?,  tanggal_baca = ?, tanggal_upload = NOW() WHERE periode = ? AND id_pelanggan = ? AND tanggal_baca IS NULL";
		$this->db->trans_begin();
		foreach ($data as $da) {
			$foto = 'http://10.10.222.236:8084/images/' . $cBlth . $id . '/' . $da['txtFoto'];
			$query = $this->db->query("SELECT id FROM pelayanan.pelanggan WHERE no_langganan = ? ", array($da['cIdPel']));
			$row = $query->row_array();
			$name_foto_survey = null;
			if ($da['txtFotoSurvey'] != null) {
				$name_foto_survey = base_url('images/') . $cBlth . $id . '/' . $da['txtFotoSurvey'];
			}
			$this->db->query($sql, array(($da['vcLatitudeNew'] != 'null') ? $da['vcLatitudeNew'] : null, ($da['vcLongitudeNew'] != 'null') ? $da['vcLongitudeNew'] : null, ($da['telepon'] != 'null') ? $da['telepon'] : null, ($da['alamat'] != 'null') ? $da['alamat'] : null, $da['cIndikasiKelainan'], $name_foto_survey, $da['cKetSurvey'], $da['nStIni'], $da['nStIni'], $da['nPakai'], $da['cKetWM'], $da['lValidLokasi'], $foto, $da['dTglCatat'],  $periode, $row['id']));
		}

		$this->db->trans_complete();
		if ($this->db->trans_status())
			return true;
		return false;
	}

	public function getBacaan($id)
	{
		$date = date('Y-m-') . '01';
		$sql = "select count(*) from pelayanan.baca_meter where periode=? and id_pembaca=?";
		$query = $this->db->query($sql, array($date, $id));
		return $query->result_array();
	}
	public function getBacaanTerbaca($id)
	{
		$date = date('Y-m-') . '01';
		$sql = "select count(*) from pelayanan.baca_meter where periode=? and id_pembaca=?";
		$query = $this->db->query($sql, array($date, $id));
		return $query->result_array();
	}
}
