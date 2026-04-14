        </main>
    </div><!-- /.main-wrapper -->
</div><!-- /.admin-layout -->

<script>
// Toggle sidebar trên mobile
const toggle = document.getElementById('sidebarToggle');
if (toggle) {
    toggle.addEventListener('click', () => {
        document.querySelector('.sidebar').classList.toggle('sidebar--collapsed');
    });
}
// Tự đóng alert sau 4 giây
document.querySelectorAll('.alert').forEach(function(el) {
    setTimeout(() => el.style.display = 'none', 4000);
});
</script>
</body>
</html>