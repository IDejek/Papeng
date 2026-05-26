<?php
/**
 * Admin Dashboard & Menus
 * @package Anggota_PSI
 */
namespace Anggota_PSI;

if ( ! defined( 'ABSPATH' ) ) exit;

class Dashboard {

    public static function init() {
        add_action( 'admin_menu', array( __CLASS__, 'register_menus' ) );
        add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_assets' ) );
        add_action( 'wp_ajax_anggota_psi_save_member', array( __CLASS__, 'ajax_save_member' ) );
        add_action( 'wp_ajax_anggota_psi_delete_member', array( __CLASS__, 'ajax_delete_member' ) );
    }

    public static function register_menus() {
        add_menu_page(
            'Anggota PSI',
            'Anggota PSI',
            'manage_anggota',
            'anggota-psi-dashboard',
            array( __CLASS__, 'page_dashboard' ),
            'dashicons-groups',
            26
        );
        add_submenu_page( 'anggota-psi-dashboard', 'Dashboard', 'Dashboard', 'manage_anggota', 'anggota-psi-dashboard', array( __CLASS__, 'page_dashboard' ) );
        add_submenu_page( 'anggota-psi-dashboard', 'Daftar Anggota', 'Daftar Anggota', 'manage_anggota', 'anggota-psi-members', array( __CLASS__, 'page_members' ) );
        add_submenu_page( 'anggota-psi-dashboard', 'Tambah Anggota', 'Tambah Anggota', 'manage_anggota', 'anggota-psi-add', array( __CLASS__, 'page_add_member' ) );
        add_submenu_page( 'anggota-psi-dashboard', 'Import / Export', 'Import / Export', 'export_anggota', 'anggota-psi-export', array( __CLASS__, 'page_export' ) );
        add_submenu_page( 'anggota-psi-dashboard', 'Statistik', 'Statistik', 'manage_anggota', 'anggota-psi-stats', array( __CLASS__, 'page_statistics' ) );
        add_submenu_page( 'anggota-psi-dashboard', 'Activity Log', 'Activity Log', 'manage_options', 'anggota-psi-log', array( __CLASS__, 'page_log' ) );
    }

    public static function enqueue_assets( $hook ) {
        if ( strpos( $hook, 'anggota-psi' ) === false ) return;
        wp_enqueue_style( 'anggota-psi-admin', ANGGOTA_PSI_URI . 'assets/css/admin.css', array(), ANGGOTA_PSI_VERSION );
        wp_enqueue_script( 'anggota-psi-admin', ANGGOTA_PSI_URI . 'assets/js/admin.js', array( 'jquery' ), ANGGOTA_PSI_VERSION, true );
        wp_localize_script( 'anggota-psi-admin', 'anggotaPsi', array(
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'anggota_psi_nonce' ),
        ) );
    }

    public static function page_dashboard() {
        $total_active = Member::total_count( 'active' );
        $total_all = Member::total_count( 'all' );
        $total_inactive = $total_all - $total_active;
        $regions = Member::count_by_region();
        ?>
        <div class="wrap anggota-psi-wrap">
            <h1 class="anggota-psi-title"><span class="dashicons dashicons-groups"></span> Dashboard Anggota PSI Papua Pegunungan</h1>

            <div class="anggota-psi-stats-cards">
                <div class="anggota-psi-stat-card anggota-card-red">
                    <div class="anggota-stat-num"><?php echo esc_html( number_format( $total_active ) ); ?></div>
                    <div class="anggota-stat-label">Anggota Aktif</div>
                </div>
                <div class="anggota-psi-stat-card anggota-card-dark">
                    <div class="anggota-stat-num"><?php echo esc_html( number_format( $total_all ) ); ?></div>
                    <div class="anggota-stat-label">Total Anggota</div>
                </div>
                <div class="anggota-psi-stat-card anggota-card-gray">
                    <div class="anggota-stat-num"><?php echo esc_html( number_format( $total_inactive ) ); ?></div>
                    <div class="anggota-stat-label">Non-Aktif</div>
                </div>
                <div class="anggota-psi-stat-card anggota-card-gold">
                    <div class="anggota-stat-num"><?php echo esc_html( count( $regions ) ); ?></div>
                    <div class="anggota-stat-label">Wilayah DPD</div>
                </div>
            </div>

            <?php if ( ! empty( $regions ) ) : ?>
            <div class="anggota-psi-card">
                <h2>Anggota per Wilayah DPD</h2>
                <table class="anggota-psi-table">
                    <thead><tr><th>Wilayah DPD</th><th>Jumlah Anggota</th><th>Persentase</th></tr></thead>
                    <tbody>
                        <?php foreach ( $regions as $r ) :
                            $pct = $total_active > 0 ? round( ( $r->total / $total_active ) * 100, 1 ) : 0;
                        ?>
                        <tr>
                            <td><strong><?php echo esc_html( $r->dpd_region ); ?></strong></td>
                            <td><?php echo esc_html( number_format( $r->total ) ); ?></td>
                            <td>
                                <div class="anggota-bar-wrap"><div class="anggota-bar" style="width:<?php echo esc_attr( $pct ); ?>%"></div></div>
                                <span class="anggota-pct"><?php echo esc_html( $pct ); ?>%</span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>

            <div class="anggota-psi-card">
                <h2>Aksi Cepat</h2>
                <div class="anggota-quick-actions">
                    <a href="<?php echo esc_url( admin_url( 'admin.php?page=anggota-psi-add' ) ); ?>" class="button button-primary"><span class="dashicons dashicons-plus-alt2"></span> Tambah Anggota</a>
                    <a href="<?php echo esc_url( admin_url( 'admin.php?page=anggota-psi-export' ) ); ?>" class="button"><span class="dashicons dashicons-download"></span> Import / Export</a>
                    <a href="<?php echo esc_url( admin_url( 'admin.php?page=anggota-psi-stats' ) ); ?>" class="button"><span class="dashicons dashicons-chart-bar"></span> Statistik Lengkap</a>
                    <a href="<?php echo esc_url( admin_url( 'admin.php?page=anggota-psi-members' ) ); ?>" class="button"><span class="dashicons dashicons-list-view"></span> Lihat Semua Anggota</a>
                </div>
            </div>
        </div>
        <?php
    }

    public static function page_members() {
        $page = isset( $_GET['paged'] ) ? absint( $_GET['paged'] ) : 1;
        $search = isset( $_GET['s'] ) ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : '';
        $region = isset( $_GET['region'] ) ? sanitize_text_field( wp_unslash( $_GET['region'] ) ) : '';
        $status = isset( $_GET['status'] ) ? sanitize_text_field( wp_unslash( $_GET['status'] ) ) : '';

        $args = array( 'page' => $page, 'per_page' => 20 );
        if ( $search ) $args['search'] = $search;
        if ( $region ) $args['dpd_region'] = $region;
        if ( $status ) $args['status'] = $status;

        $data = Member::get_all( $args );
        $regions = Member::get_regions();
        ?>
        <div class="wrap anggota-psi-wrap">
            <h1 class="anggota-psi-title"><span class="dashicons dashicons-list-view"></span> Daftar Anggota</h1>

            <div class="anggota-psi-filters">
                <form method="get" class="anggota-filter-form">
                    <input type="hidden" name="page" value="anggota-psi-members">
                    <input type="search" name="s" value="<?php echo esc_attr( $search ); ?>" placeholder="Cari nama, ID, email, telepon..." class="anggota-search-input">
                    <select name="region">
                        <option value="">Semua Wilayah</option>
                        <?php foreach ( $regions as $r ) : ?>
                            <option value="<?php echo esc_attr( $r ); ?>" <?php selected( $region, $r ); ?>><?php echo esc_html( $r ); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <select name="status">
                        <option value="">Semua Status</option>
                        <option value="active" <?php selected( $status, 'active' ); ?>>Aktif</option>
                        <option value="inactive" <?php selected( $status, 'inactive' ); ?>>Non-Aktif</option>
                    </select>
                    <button type="submit" class="button">Filter</button>
                    <a href="<?php echo esc_url( admin_url( 'admin.php?page=anggota-psi-members' ) ); ?>" class="button">Reset</a>
                </form>
            </div>

            <div class="anggota-psi-card">
                <div class="anggota-psi-table-wrap">
                    <table class="anggota-psi-table anggota-psi-table-striped">
                        <thead>
                            <tr>
                                <th>ID Anggota</th>
                                <th>Nama Lengkap</th>
                                <th>Email</th>
                                <th>Telepon</th>
                                <th>DPD Wilayah</th>
                                <th>Tgl Bergabung</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ( ! empty( $data['rows'] ) ) : foreach ( $data['rows'] as $m ) : ?>
                            <tr>
                                <td><code><?php echo esc_html( $m->member_id ); ?></code></td>
                                <td><strong><?php echo esc_html( $m->full_name ); ?></strong></td>
                                <td><?php echo esc_html( $m->email ); ?></td>
                                <td><?php echo esc_html( $m->phone ); ?></td>
                                <td><?php echo esc_html( $m->dpd_region ); ?></td>
                                <td><?php echo esc_html( date( 'd M Y', strtotime( $m->join_date ) ) ); ?></td>
                                <td><span class="anggota-status anggota-status-<?php echo esc_attr( $m->status ); ?>"><?php echo $m->status === 'active' ? 'Aktif' : 'Non-Aktif'; ?></span></td>
                                <td>
                                    <button class="button button-small anggota-delete-btn" data-id="<?php echo esc_attr( $m->id ); ?>" data-name="<?php echo esc_attr( $m->full_name ); ?>">Hapus</button>
                                </td>
                            </tr>
                            <?php endforeach; else : ?>
                            <tr><td colspan="8" class="anggota-empty">Tidak ada data anggota.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php if ( $data['total_pages'] > 1 ) : ?>
                <div class="anggota-pagination">
                    <?php
                    for ( $i = 1; $i <= $data['total_pages']; $i++ ) {
                        $url = add_query_arg( array_merge( $_GET, array( 'paged' => $i, 'page' => 'anggota-psi-members' ) ) );
                        $active = $i === $page ? ' button-primary' : '';
                        echo '<a href="' . esc_url( $url ) . '" class="button' . $active . '">' . $i . '</a> ';
                    }
                    ?>
                </div>
                <?php endif; ?>
                <p class="anggota-total-info">Menampilkan <?php echo esc_html( count( $data['rows'] ) ); ?> dari <?php echo esc_html( number_format( $data['total'] ) ); ?> anggota</p>
            </div>
        </div>
        <?php
    }

    public static function page_add_member() {
        $regions = array( 'Jayawijaya', 'Yahukimo', 'Pegunungan Bintang', 'Tolikara', 'Mamberamo Tengah', 'Yalimo', 'Lanny Jaya', 'Nduga' );
        ?>
        <div class="wrap anggota-psi-wrap">
            <h1 class="anggota-psi-title"><span class="dashicons dashicons-plus-alt2"></span> Tambah Anggota Baru</h1>

            <div class="anggota-psi-card">
                <form id="anggota-psi-form" method="post" class="anggota-form">
                    <?php wp_nonce_field( 'anggota_psi_nonce', 'anggota_nonce_field' ); ?>
                    <div class="anggota-form-grid">
                        <div class="anggota-form-group">
                            <label for="full_name">Nama Lengkap <span class="required">*</span></label>
                            <input type="text" id="full_name" name="full_name" required placeholder="Masukkan nama lengkap">
                        </div>
                        <div class="anggota-form-group">
                            <label for="nik">NIK</label>
                            <input type="text" id="nik" name="nik" maxlength="16" placeholder="Nomor Induk Kependudukan">
                        </div>
                        <div class="anggota-form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" placeholder="email@contoh.com">
                        </div>
                        <div class="anggota-form-group">
                            <label for="phone">No. Telepon / WhatsApp</label>
                            <input type="text" id="phone" name="phone" placeholder="08xxxxxxxxxx">
                        </div>
                        <div class="anggota-form-group">
                            <label for="dpd_region">DPD Wilayah</label>
                            <select id="dpd_region" name="dpd_region">
                                <option value="">-- Pilih Wilayah --</option>
                                <?php foreach ( $regions as $r ) : ?>
                                    <option value="<?php echo esc_attr( $r ); ?>"><?php echo esc_html( $r ); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="anggota-form-group">
                            <label for="join_date">Tanggal Bergabung</label>
                            <input type="date" id="join_date" name="join_date" value="<?php echo esc_attr( current_time( 'Y-m-d' ) ); ?>">
                        </div>
                        <div class="anggota-form-group anggota-form-full">
                            <label for="address">Alamat</label>
                            <textarea id="address" name="address" rows="3" placeholder="Alamat lengkap"></textarea>
                        </div>
                        <div class="anggota-form-group">
                            <label for="status">Status</label>
                            <select id="status" name="status">
                                <option value="active">Aktif</option>
                                <option value="inactive">Non-Aktif</option>
                            </select>
                        </div>
                        <div class="anggota-form-group anggota-form-full">
                            <label for="notes">Catatan</label>
                            <textarea id="notes" name="notes" rows="2" placeholder="Catatan tambahan (opsional)"></textarea>
                        </div>
                    </div>
                    <div class="anggota-form-actions">
                        <button type="submit" class="button button-primary button-large" id="anggota-submit-btn">
                            <span class="dashicons dashicons-plus-alt2"></span> Simpan Anggota
                        </button>
                        <span id="anggota-result-msg" style="margin-left:1rem;"></span>
                    </div>
                </form>
            </div>
        </div>
        <?php
    }

    public static function ajax_save_member() {
        check_ajax_referer( 'anggota_psi_nonce', 'nonce' );
        if ( ! current_user_can( 'manage_anggota' ) ) wp_send_json_error( array( 'message' => 'Akses ditolak.' ) );

        $data = array(
            'full_name'  => $_POST['full_name'] ?? '',
            'nik'        => $_POST['nik'] ?? '',
            'email'      => $_POST['email'] ?? '',
            'phone'      => $_POST['phone'] ?? '',
            'dpd_region' => $_POST['dpd_region'] ?? '',
            'address'    => $_POST['address'] ?? '',
            'join_date'  => $_POST['join_date'] ?? '',
            'status'     => $_POST['status'] ?? 'active',
            'notes'      => $_POST['notes'] ?? '',
        );

        if ( empty( $data['full_name'] ) ) {
            wp_send_json_error( array( 'message' => 'Nama lengkap wajib diisi.' ) );
        }

        $member_id = Member::add( $data );

        if ( $member_id ) {
            wp_send_json_success( array( 'message' => 'Anggota berhasil ditambahkan dengan ID: ' . $member_id, 'member_id' => $member_id ) );
        } else {
            wp_send_json_error( array( 'message' => 'Gagal menambahkan anggota.' ) );
        }
    }

    public static function ajax_delete_member() {
        check_ajax_referer( 'anggota_psi_nonce', 'nonce' );
        if ( ! current_user_can( 'manage_anggota' ) ) wp_send_json_error( array( 'message' => 'Akses ditolak.' ) );

        $id = absint( $_POST['id'] ?? 0 );
        if ( ! $id ) wp_send_json_error( array( 'message' => 'ID tidak valid.' ) );

        $result = Member::delete( $id );
        if ( $result ) {
            wp_send_json_success( array( 'message' => 'Anggota berhasil dihapus.' ) );
        } else {
            wp_send_json_error( array( 'message' => 'Gagal menghapus anggota.' ) );
        }
    }

    public static function page_export() {
        ?>
        <div class="wrap anggota-psi-wrap">
            <h1 class="anggota-psi-title"><span class="dashicons dashicons-download"></span> Import / Export Data</h1>

            <div class="anggota-psi-grid-2">
                <!-- Export -->
                <div class="anggota-psi-card">
                    <h2>Export Data Anggota</h2>
                    <p>Download data anggota dalam format CSV atau Excel.</p>
                    <div class="anggota-export-actions">
                        <a href="<?php echo esc_url( admin_url( 'admin-ajax.php?action=anggota_psi_export_csv&nonce=' . wp_create_nonce( 'anggota_psi_nonce' ) ) ); ?>" class="button button-primary"><span class="dashicons dashicons-media-spreadsheet"></span> Export CSV</a>
                        <a href="<?php echo esc_url( admin_url( 'admin-ajax.php?action=anggota_psi_export_excel&nonce=' . wp_create_nonce( 'anggota_psi_nonce' ) ) ); ?>" class="button"><span class="dashicons dashicons-media-document"></span> Export Excel (CSV UTF-8 BOM)</a>
                    </div>
                </div>

                <!-- Import -->
                <div class="anggota-psi-card">
                    <h2>Import Data Anggota</h2>
                    <p>Upload file CSV/Excel dengan kolom: <strong>Nama Lengkap, NIK, Email, Telepon, DPD, Alamat, Tanggal Bergabung</strong></p>
                    <form id="anggota-import-form" enctype="multipart/form-data">
                        <?php wp_nonce_field( 'anggota_psi_nonce', 'import_nonce' ); ?>
                        <input type="file" name="import_file" accept=".csv,.xlsx,.xls" required>
                        <button type="submit" class="button button-primary" id="anggota-import-btn">
                            <span class="dashicons dashicons-upload"></span> Import File
                        </button>
                        <span id="import-result-msg" style="margin-left:1rem;"></span>
                    </form>
                </div>
            </div>
        </div>
        <?php
    }

    public static function page_statistics() {
        $total_active = Member::total_count( 'active' );
        $total_all = Member::total_count( 'all' );
        $regions = Member::count_by_region();
        $monthly = self::get_monthly_growth();
        ?>
        <div class="wrap anggota-psi-wrap">
            <h1 class="anggota-psi-title"><span class="dashicons dashicons-chart-bar"></span> Statistik Keanggotaan</h1>

            <div class="anggota-psi-stats-cards">
                <div class="anggota-psi-stat-card anggota-card-red">
                    <div class="anggota-stat-num"><?php echo esc_html( number_format( $total_active ) ); ?></div>
                    <div class="anggota-stat-label">Anggota Aktif</div>
                </div>
                <div class="anggota-psi-stat-card anggota-card-dark">
                    <div class="anggota-stat-num"><?php echo esc_html( number_format( $total_all ) ); ?></div>
                    <div class="anggota-stat-label">Total Semua</div>
                </div>
            </div>

            <?php if ( ! empty( $regions ) ) : ?>
            <div class="anggota-psi-card">
                <h2>Distribusi per Wilayah</h2>
                <div class="anggota-chart-bars">
                    <?php
                    $max_val = 0;
                    foreach ( $regions as $r ) { if ( $r->total > $max_val ) $max_val = $r->total; }
                    foreach ( $regions as $r ) :
                        $pct = $max_val > 0 ? ( $r->total / $max_val ) * 100 : 0;
                    ?>
                        <div class="anggota-chart-row">
                            <span class="anggota-chart-label"><?php echo esc_html( $r->dpd_region ); ?></span>
                            <div class="anggota-chart-bar-wrap">
                                <div class="anggota-chart-bar" style="width:<?php echo esc_attr( $pct ); ?>%"></div>
                            </div>
                            <span class="anggota-chart-val"><?php echo esc_html( number_format( $r->total ) ); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if ( ! empty( $monthly ) ) : ?>
            <div class="anggota-psi-card">
                <h2>Pertumbuhan Bulanan (6 Bulan Terakhir)</h2>
                <div class="anggota-chart-bars">
                    <?php
                    $max_m = 0;
                    foreach ( $monthly as $m ) { if ( $m->total > $max_m ) $max_m = $m->total; }
                    foreach ( $monthly as $m ) :
                        $pct = $max_m > 0 ? ( $m->total / $max_m ) * 100 : 0;
                    ?>
                        <div class="anggota-chart-row">
                            <span class="anggota-chart-label"><?php echo esc_html( $m->month ); ?></span>
                            <div class="anggota-chart-bar-wrap">
                                <div class="anggota-chart-bar anggota-chart-bar-gold" style="width:<?php echo esc_attr( $pct ); ?>%"></div>
                            </div>
                            <span class="anggota-chart-val"><?php echo esc_html( number_format( $m->total ) ); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <?php
    }

    private static function get_monthly_growth() {
        global $wpdb;
        $table = Database::get_members_table();
        $results = $wpdb->get_results( "
            SELECT DATE_FORMAT(join_date, '%Y-%m') as month, COUNT(*) as total
            FROM {$table}
            WHERE join_date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
            GROUP BY month ORDER BY month ASC
        " );
        return $results;
    }

    public static function page_log() {
        global $wpdb;
        $log_table = Database::get_log_table();
        $logs = $wpdb->get_results( "SELECT * FROM {$log_table} ORDER BY created_at DESC LIMIT 200" );
        ?>
        <div class="wrap anggota-psi-wrap">
            <h1 class="anggota-psi-title"><span class="dashicons dashicons-clock"></span> Activity Log</h1>
            <div class="anggota-psi-card">
                <div class="anggota-psi-table-wrap">
                    <table class="anggota-psi-table anggota-psi-table-striped">
                        <thead><tr><th>Waktu</th><th>User</th><th>Aksi</th><th>Detail</th><th>IP</th></tr></thead>
                        <tbody>
                            <?php if ( ! empty( $logs ) ) : foreach ( $logs as $l ) :
                                $user = get_userdata( $l->user_id );
                                $uname = $user ? $user->display_name : 'Guest';
                            ?>
                            <tr>
                                <td><?php echo esc_html( date( 'd M Y H:i', strtotime( $l->created_at ) ) ); ?></td>
                                <td><?php echo esc_html( $uname ); ?></td>
                                <td><code><?php echo esc_html( $l->action ); ?></code></td>
                                <td><?php echo esc_html( $l->details ); ?></td>
                                <td><code><?php echo esc_html( $l->ip_address ); ?></code></td>
                            </tr>
                            <?php endforeach; else : ?>
                            <tr><td colspan="5" class="anggota-empty">Belum ada log aktivitas.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php
    }
}
