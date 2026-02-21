<?php if (isset($totalPages) && $totalPages > 1): ?>

<style>
   
    .pagination-wrapper {
        margin-top: 40px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .pagination {
        display: flex;
        align-items: center;
        gap: 24px;
        font-size: 14px;
        font-weight: 500;
    }

    .pagination a {
        position: relative;
        text-decoration: none;
        color: #4b5563;
        padding: 4px 2px;
        transition: all 0.25s ease;
    }

    .pagination a::after {
        content: "";
        position: absolute;
        left: 0;
        bottom: -6px;
        width: 0%;
        height: 2px;
        background: #459efe;
        transition: width 0.25s ease;
    }

    .pagination a:hover {
        color: #111827;
    }

    .pagination a:hover::after {
        width: 100%;
    }

    .pagination a.active {
        color: #111827;
        font-weight: 600;
    }

    .pagination a.active::after {
        width: 100%;
    }

    .pagination .nav-btn {
        font-size: 16px;
        color: #6a6c6e;
        letter-spacing: 0.3px;
    }

    .pagination .nav-btn:hover {
        color: #111827;
    }
</style>

<div class="pagination-wrapper">
    <div class="pagination">

        <!-- Previous -->
        <?php if ($currentPage > 1): ?>
            <a href="?<?= http_build_query(array_merge($_GET, ['page' => $currentPage - 1])) ?>"
               class="nav-btn">
                « Previous
            </a>
        <?php endif; ?>

        <!-- Page Numbers -->
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>"
               class="<?= $i == $currentPage ? 'active' : '' ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>

        <!-- Next -->
        <?php if ($currentPage < $totalPages): ?>
            <a href="?<?= http_build_query(array_merge($_GET, ['page' => $currentPage + 1])) ?>"
               class="nav-btn">
                Next »
            </a>
        <?php endif; ?>

    </div>
</div>

<?php endif; ?>
