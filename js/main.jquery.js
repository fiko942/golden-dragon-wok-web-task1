$(function () {
  const $header = $('[data-header]');
  const $backToTop = $('[data-back-to-top]');
  const menuDataPath = './js/data.menu.json';
  let menuData = [];
  let menuLoaded = false;

  /* Smooth scroll navigation */
  $('[data-scroll]').on('click', function (event) {
    const target = $(this).attr('href');
    if (target && target.startsWith('#')) {
      event.preventDefault();
      const $target = $(target);
      if ($target.length) {
        $('html, body').animate({ scrollTop: $target.offset().top - 90 }, 500, 'swing');
      }
    }
  });

  /* Header shrink + back-to-top visibility */
  const handleScroll = () => {
    const scrolled = $(window).scrollTop() > 80;
    $header.toggleClass('scrolled header-scrolled', scrolled);
    if ($backToTop.length) {
      $backToTop.toggleClass('is-visible', scrolled);
    }
  };

  $(window).on('scroll', handleScroll);
  handleScroll();

  /* Signature dishes on home */
  const $signatureContainer = $('#signature-list');
  if ($signatureContainer.length) {
    fetchMenuData().then(() => {
      const signatures = menuData.filter((item) => item.signature);
      renderSignatureCards(signatures, $signatureContainer);
    });
  }

  /* Menu page interactions */
  const $menuGrid = $('#menu-grid');
  if ($menuGrid.length) {
    const $categoryFilters = $('[data-filter-category] button');
    const $tagFilters = $('[data-filter-tag] input[type="checkbox"]');
    const $searchInput = $('#menu-search');
    const $sortSelect = $('#menu-sort');
    const $modal = $('#menu-modal');
    const $modalOverlay = $('#menu-modal-overlay');

    fetchMenuData().then(() => {
      renderMenu(menuData, $menuGrid);
      applyFilters();
    });

    let activeCategory = 'All';

    $categoryFilters.on('click', function () {
      activeCategory = $(this).data('category');
      $categoryFilters
        .removeClass('bg-[#9A1111] text-neutral-100')
        .addClass('bg-neutral-200 text-neutral-800 dark:bg-neutral-800 dark:text-neutral-200');
      $(this)
        .removeClass('bg-neutral-200 text-neutral-800 dark:bg-neutral-800 dark:text-neutral-200')
        .addClass('bg-[#9A1111] text-neutral-100');
      applyFilters();
    });

    $tagFilters.on('change', applyFilters);
    $searchInput.on('input', applyFilters);
    $sortSelect.on('change', applyFilters);

    $menuGrid.on('click keypress', '[data-menu-card]', function (event) {
      if (event.type === 'click' || event.key === 'Enter') {
        const id = $(this).data('id');
        const item = menuData.find((dish) => dish.id === id);
        if (!item) return;
        openModal(item, this);
      }
    });

    $('[data-modal-close]').on('click', closeModal);
    $modalOverlay.on('click', closeModal);
    $(document).on('keydown', function (event) {
      if (event.key === 'Escape' && $modal.hasClass('open')) {
        closeModal();
      }
    });

    function applyFilters() {
      let filtered = [...menuData];

      if (activeCategory && activeCategory !== 'All') {
        filtered = filtered.filter((item) => item.category === activeCategory);
      }

      const tags = $tagFilters
        .filter(':checked')
        .map(function () {
          return $(this).data('tag');
        })
        .get();

      if (tags.length) {
        filtered = filtered.filter((item) => tags.every((tag) => item[tag] === true));
      }

      const term = ($searchInput.val() || '').toString().toLowerCase();
      if (term) {
        filtered = filtered.filter((item) =>
          [item.name, item.desc].some((text) => text.toLowerCase().includes(term))
        );
      }

      const sortValue = $sortSelect.val();
      if (sortValue === 'popular') {
        filtered.sort((a, b) => Number(b.popular) - Number(a.popular));
      } else if (sortValue === 'price-asc') {
        filtered.sort((a, b) => a.price - b.price);
      } else if (sortValue === 'price-desc') {
        filtered.sort((a, b) => b.price - a.price);
      }

      renderMenu(filtered, $menuGrid);
    }

    function openModal(item, triggerEl) {
      if (!$modal.length) return;
      $modal.find('[data-modal-title]').text(item.name);
      $modal.find('[data-modal-image]').attr({ src: item.image, alt: item.name });
      $modal.find('[data-modal-desc]').text(item.desc);
      $modal.find('[data-modal-price]').text(formatCurrency(item.price));
      $modal
        .find('[data-modal-allergens]')
        .text(item.allergens.length ? item.allergens.join(', ') : 'Tidak ada informasi alergen.');
      const $tagWrap = $modal.find('[data-modal-tags]').empty();
      ['spicy', 'vegetarian', 'halalFriendly'].forEach((tag) => {
        if (item[tag]) {
          const labelMap = {
            spicy: 'Pedas',
            vegetarian: 'Vegetarian',
            halalFriendly: 'Halal-Friendly'
          };
          $('<span/>', {
            class: 'badge-icon bg-[#C8A951] text-neutral-900 font-medium'
          })
            .text(labelMap[tag])
            .appendTo($tagWrap);
        }
      });

      $modal.removeClass('invisible opacity-0').addClass('open').attr('aria-hidden', 'false');
      $('body').addClass('modal-open');
      $modalOverlay.removeClass('hidden');

      const focusable = $modal
        .find('a[href], button:not([disabled]), textarea, input, select, [tabindex]:not([tabindex="-1"])')
        .filter(':visible');
      const first = focusable.first();
      const last = focusable.last();

      $modal.data('restore-focus', triggerEl);

      $(document).on('keydown.gdwModal', function (event) {
        if (event.key !== 'Tab') return;
        if (!focusable.length) return;
        if (event.shiftKey && document.activeElement === first[0]) {
          event.preventDefault();
          last[0].focus();
        } else if (!event.shiftKey && document.activeElement === last[0]) {
          event.preventDefault();
          first[0].focus();
        }
      });

      setTimeout(() => {
        first.trigger('focus');
      }, 50);
    }

    function closeModal() {
      if (!$modal.length) return;
      $modal.addClass('opacity-0 invisible').removeClass('open').attr('aria-hidden', 'true');
      $('body').removeClass('modal-open');
      $modalOverlay.addClass('hidden');
      $(document).off('keydown.gdwModal');
      const restore = $modal.data('restore-focus');
      if (restore) {
        $(restore).trigger('focus');
      }
    }
  }

  /* Back to top */
  if ($backToTop.length) {
    $backToTop.on('click', function () {
      $('html, body').animate({ scrollTop: 0 }, 500, 'swing');
    });
  }

  /* Form validation hints & AJAX */
  const $jobForm = $('#job-form');
  if ($jobForm.length) {
    const $submitBtn = $jobForm.find('button[type="submit"]');
    const $spinner = $submitBtn.find('[data-spinner]');

    $jobForm.find('[data-validate]').on('blur input change', function () {
      validateField($(this));
    });

    $jobForm.on('submit', function (event) {
      event.preventDefault();
      let isValid = true;

      $jobForm.find('[data-validate]').each(function () {
        if (!validateField($(this))) {
          isValid = false;
        }
      });

      const $captchaInput = $('[data-captcha-input]');
      const expected = Number($captchaInput.data('answer'));
      if ($captchaInput.val().trim() !== expected.toString()) {
        setFieldState($captchaInput, false, 'Jawaban captcha salah.');
        isValid = false;
      }

      if (!isValid) {
        return;
      }

      const formData = new FormData($jobForm[0]);
      toggleSubmitting(true);

      $.ajax({
        url: '/php/applyJob.php',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json'
      })
        .done((response) => {
          showNotification(response.success, response.message || 'Terima kasih, permintaan Anda diterima.');
          if (response.success) {
            $jobForm[0].reset();
            $jobForm.find('[data-hint]').text('');
          }
        })
        .fail(() => {
          showNotification(false, 'Terjadi kendala jaringan. Silakan coba lagi.');
        })
        .always(() => {
          toggleSubmitting(false);
        });
    });

    function toggleSubmitting(isSubmitting) {
      if (isSubmitting) {
        $submitBtn.prop('disabled', true).addClass('opacity-60');
        $spinner.removeClass('hidden');
      } else {
        $submitBtn.prop('disabled', false).removeClass('opacity-60');
        $spinner.addClass('hidden');
      }
    }

    function showNotification(success, message) {
      const $alert = $('[data-alert]');
      if ($alert.length) {
        $alert
          .removeClass('hidden')
          .toggleClass('bg-green-100 text-green-900 border-green-300', success)
          .toggleClass('bg-red-100 text-red-900 border-red-300', !success);
        $alert.find('[data-alert-message]').text(message);
      }
    }

    function validateField($field) {
      const value = ($field.val() || '').toString().trim();
      const type = $field.data('validate');
      let valid = true;
      let message = '';

      if ($field.prop('required') && !value) {
        valid = false;
        message = 'Wajib diisi.';
      }

      if (valid && value) {
        if (type === 'email') {
          valid = /^[\w-.]+@([\w-]+\.)+[\w-]{2,}$/.test(value);
          if (valid) {
            valid = value.toLowerCase().endsWith('@gmail.com');
            message = valid ? '' : 'Gunakan alamat Gmail (contoh: nama@gmail.com).';
          } else {
            message = 'Format email tidak valid.';
          }
        } else if (type === 'phone') {
          valid = /^\+?62\d{8,13}$/.test(value);
          message = valid ? '' : 'Format nomor Indonesia, contoh: +628123456789.';
        } else if (type === 'minlength') {
          const min = Number($field.attr('minlength')) || 0;
          valid = value.length >= min;
          message = valid ? '' : `Minimal ${min} karakter.`;
        } else if (type === 'file') {
          const file = $field[0].files[0];
          if (file) {
            const allowed = [
              'application/pdf',
              'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
              'application/msword'
            ];
            valid = allowed.includes(file.type) && file.size <= 2 * 1024 * 1024;
            message = valid ? '' : 'Format PDF atau DOCX, maksimal 2MB.';
          }
        } else if (type === 'checkbox') {
          valid = $field.is(':checked');
          message = valid ? '' : 'Silakan setujui kebijakan data.';
        }
      }

      setFieldState($field, valid, message);
      return valid;
    }

    function setFieldState($field, valid, message) {
      const $hint = $field.closest('[data-field-wrap]').find('[data-hint]');
      if (!valid) {
        $field.addClass('border-red-500');
        $hint
          .removeClass('text-green-600')
          .addClass('text-red-600')
          .text(message || 'Periksa kembali input Anda.');
      } else {
        $field.removeClass('border-red-500');
        if (message) {
          $hint.text(message).removeClass('text-red-600').addClass('text-green-600');
        } else {
          $hint.text('').removeClass('text-red-600 text-green-600');
        }
      }
    }
  }

  function renderSignatureCards(items, $container) {
    $container.empty();
    items.slice(0, 6).forEach((item) => {
      const $card = $('<article/>', {
        class: 'card-hover border border-transparent rounded-xl overflow-hidden bg-white dark:bg-neutral-900 shadow-md transition dark:border-neutral-800'
      }).append(
        $('<img/>', {
          src: item.image,
          alt: item.name,
          class: 'h-48 w-full object-cover',
          loading: 'lazy'
        }),
        $('<div/>', { class: 'p-5 space-y-3' }).append(
          $('<h3/>', { class: 'font-semibold text-lg text-neutral-900 dark:text-neutral-100' }).text(item.name),
          $('<p/>', { class: 'text-sm text-neutral-600 dark:text-neutral-300' }).text(item.desc),
          $('<div/>', { class: 'flex items-center justify-between text-sm font-semibold' }).append(
            $('<span/>', { class: 'text-[#9A1111]' }).text(formatCurrency(item.price)),
            buildBadges(item)
          )
        )
      );
      $container.append($card);
    });
  }

  function renderMenu(items, $container) {
    $container.empty();
    if (!items.length) {
      $container.append(
        '<p class="col-span-full text-center text-neutral-500">Menu tidak ditemukan. Coba ubah filter.</p>'
      );
      return;
    }

    items.forEach((item) => {
      const $card = $('<article/>', {
        class:
          'group bg-white dark:bg-neutral-900 border border-transparent rounded-2xl overflow-hidden shadow-lg card-hover focus-within:ring-2 focus-within:ring-[#C8A951]',
        tabindex: 0,
        'data-menu-card': true,
        'data-id': item.id,
        role: 'button',
        'aria-label': `Detail menu ${item.name}`
      }).append(
        $('<div/>', { class: 'relative' }).append(
          $('<img/>', {
            src: item.image,
            alt: item.name,
            class: 'h-48 w-full object-cover transition duration-300 group-hover:scale-105',
            loading: 'lazy'
          })
        ),
        $('<div/>', { class: 'p-5 space-y-3' }).append(
          $('<div/>', { class: 'flex items-center justify-between' }).append(
            $('<h3/>', { class: 'font-semibold text-lg text-neutral-900 dark:text-neutral-100' }).text(item.name),
            $('<span/>', { class: 'text-[#9A1111] font-semibold' }).text(formatCurrency(item.price))
          ),
          $('<p/>', { class: 'text-sm text-neutral-600 dark:text-neutral-300' }).text(item.desc),
          buildBadges(item)
        )
      );
      $container.append($card);
    });
  }

  function buildBadges(item) {
    const $wrapper = $('<div/>', {
      class: 'flex flex-wrap gap-2 text-xs font-medium text-neutral-700 dark:text-neutral-200'
    });
    if (item.spicy) {
      $wrapper.append(
        '<span class="badge-icon bg-red-100 text-red-700"><img src="/assets/icons/spicy.svg" alt="Ikon pedas" class="h-4 w-4" />Pedas</span>'
      );
    }
    if (item.vegetarian) {
      $wrapper.append(
        '<span class="badge-icon bg-emerald-100 text-emerald-700"><img src="/assets/icons/veg.svg" alt="Ikon vegetarian" class="h-4 w-4" />Vegetarian</span>'
      );
    }
    if (item.halalFriendly) {
      $wrapper.append(
        '<span class="badge-icon bg-lime-100 text-lime-700"><img src="/assets/icons/halal.svg" alt="Ikon halal" class="h-4 w-4" />Halal-Friendly</span>'
      );
    }
    if (item.popular) {
      $wrapper.append('<span class="badge-icon bg-[#C8A951] text-neutral-900">Favorit</span>');
    }
    if (item.signature) {
      $wrapper.append('<span class="badge-icon bg-[#9A1111] text-neutral-100">Signature</span>');
    }
    return $wrapper;
  }

  function formatCurrency(price) {
    return new Intl.NumberFormat('id-ID', {
      style: 'currency',
      currency: 'IDR',
      minimumFractionDigits: 0
    }).format(price);
  }

  function fetchMenuData() {
    if (menuLoaded) {
      return $.Deferred().resolve(menuData).promise();
    }
    return $.getJSON(menuDataPath).then((data) => {
      menuData = data;
      menuLoaded = true;
      return menuData;
    });
  }
});
