<?php
// public/index.php
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>BC Apparel – Custom Printing</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="../src/output.css" rel="stylesheet">

</head>
<body class="bg-slate-50 text-slate-900">
  <div class="min-h-screen max-w-7xl mx-auto px-4 py-8">
    <!-- Top bar -->
    <header class="mb-8 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
      <div>
        <h1 class="text-2xl sm:text-3xl font-semibold tracking-tight">
          BC Apparel – Custom Print Builder
        </h1>
        <p class="text-sm text-slate-500 mt-1">
          Upload your artwork, then choose the perfect garment, color, size, and quantity.
        </p>
      </div>
      <div class="flex items-center gap-2 justify-end">
        <span class="inline-flex items-center rounded-full bg-slate-900 px-3 py-1 text-[11px] font-medium text-white uppercase tracking-wide">
          Internal Build
        </span>
      </div>
    </header>

    <!-- Stepper -->
    <div class="mb-8 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
      <div class="flex items-center gap-4">
        <div class="flex items-center gap-3">
          <div class="flex h-8 w-8 items-center justify-center rounded-full bg-black text-xs font-semibold text-white">
            1
          </div>
          <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-700">
              Upload Artwork
            </p>
            <p class="text-xs text-slate-500">
              Start with the design you’d like to print.
            </p>
          </div>
        </div>

        <div class="hidden sm:block h-px flex-1 bg-slate-200"></div>

        <div class="flex items-center gap-3">
          <div class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-900/5 text-xs font-semibold text-slate-500" id="step-2-circle">
            2
          </div>
          <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-700">
              Choose Garment & Options
            </p>
            <p class="text-xs text-slate-500">
              Pick style, color, size, and quantity.
            </p>
          </div>
        </div>
      </div>

      <span
        id="step-2-badge"
        class="self-start inline-flex items-center rounded-full bg-slate-200 px-3 py-1 text-[11px] font-medium text-slate-600"
      >
        Upload artwork to unlock garment selection
      </span>
    </div>

    <div class="grid gap-8 lg:grid-cols-[minmax(0,1.4fr)_minmax(0,2fr)] items-start">
      <!-- LEFT COLUMN: STEP 1 -->
      <section class="space-y-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
          <h2 class="text-sm font-semibold text-slate-800 mb-1">
            Step 1 · Upload your artwork
          </h2>
          <p class="text-xs text-slate-500 mb-4">
            Supported formats: PNG, JPG, SVG, PDF. Max 25MB. We’ll use this artwork in the live garment preview.
          </p>

          <div
            id="upload-area"
            class="relative flex flex-col items-center justify-center rounded-xl border-2 border-dashed border-slate-300 bg-slate-50/70 px-6 py-10 sm:px-10 transition hover:border-slate-400"
          >
            <div class="flex flex-col items-center text-center gap-4">
              <div
                id="art-preview-wrapper"
                class="w-32 h-32 rounded-xl border border-slate-200 bg-white flex items-center justify-center overflow-hidden shadow-inner"
              >
                <span id="art-preview-placeholder" class="text-[11px] text-slate-400">
                  Artwork preview
                </span>
                <img
                  id="art-preview"
                  alt="Artwork preview"
                  class="hidden max-h-full max-w-full object-contain"
                />
              </div>

              <div>
                <p class="text-sm font-medium text-slate-800">Drag & drop your file here</p>
                <p class="text-xs text-slate-500 mt-1">
                  or click to browse. High-resolution files work best for print quality.
                </p>
              </div>

              <div class="flex flex-col sm:flex-row items-center gap-2">
                <span class="rounded-full bg-white px-3 py-1 text-[11px] text-slate-500 border border-slate-200">
                  Max size 25MB · RGB or CMYK
                </span>
              </div>

              <input
                id="art-upload-input"
                type="file"
                accept="image/png,image/jpeg,image/svg+xml,application/pdf"
                class="absolute inset-0 h-full w-full cursor-pointer opacity-0"
              />
            </div>
          </div>

          <p id="art-upload-status" class="mt-3 text-xs text-slate-500">
            No artwork uploaded yet.
          </p>
        </div>
      </section>

      <!-- RIGHT COLUMN: STEP 2 -->
      <section
        id="step-2"
        class="space-y-5 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm opacity-40 pointer-events-none"
      >
        <!-- Products list + config -->
        <div class="grid gap-5 xl:grid-cols-[minmax(0,1.3fr)_minmax(0,1.6fr)]">
          <!-- Product list -->
          <div>
            <div class="flex items-center justify-between mb-3">
              <div>
                <h2 class="text-sm font-semibold text-slate-800">
                  Step 2 · Choose a garment
                </h2>
                <p class="text-xs text-slate-500">
                  Select from the available blanks we offer for printing.
                </p>
              </div>
            </div>

            <div
              id="products"
              class="grid gap-3 sm:grid-cols-2"
            >
              <!-- Filled by JS -->
            </div>
            <p id="products-status" class="mt-2 text-xs text-slate-500"></p>
          </div>

          <!-- Config + preview -->
          <div id="config-panel" class="rounded-xl border border-slate-200 bg-slate-50/80 p-4 flex flex-col gap-3">
            <div class="flex items-center justify-between gap-2">
              <div>
                <p class="text-xs font-semibold text-slate-800">Configuration</p>
                <p class="text-[11px] text-slate-500">
                  Fine-tune details for this print job.
                </p>
              </div>
            </div>

            <!-- When no product selected -->
            <div
              id="config-empty"
              class="flex-1 flex items-center justify-center text-center text-xs text-slate-400 border border-dashed border-slate-200 rounded-lg bg-white/60 px-4 py-6"
            >
              Select a garment on the left to configure color, size, and quantity.
            </div>

            <!-- When product selected -->
            <div id="config-content" class="hidden flex-1 flex flex-col gap-4">
              <!-- Preview row -->
              <div class="flex gap-4 flex-col sm:flex-row">
                <div class="flex-1 flex flex-col gap-2">
                  <p class="text-[11px] font-medium text-slate-600">Live preview</p>
                  <div class="relative w-full max-w-xs aspect-[4/5] bg-white rounded-lg border border-slate-200 flex items-center justify-center overflow-hidden">
                    <img
                      id="config-product-image"
                      alt="Product mockup"
                      class="max-h-full max-w-full object-contain"
                    />
                    <img
                      id="config-art-overlay"
                      alt="Artwork overlay"
                      class="pointer-events-none absolute max-h-[35%] max-w-[60%] object-contain opacity-90"
                      style="top: 40%;"
                    />
                  </div>
                </div>

                <div class="flex-1 space-y-3">
                  <!-- Title & description -->
                  <div>
                    <p class="text-sm font-semibold text-slate-900" id="config-product-title"></p>
                    <p class="text-[11px] text-slate-500 mt-1" id="config-product-description"></p>
                  </div>

                  <!-- Color selector -->
                  <div>
                    <p class="text-[11px] font-medium text-slate-600 mb-1">Color</p>
                    <div id="config-colors" class="flex flex-wrap gap-1.5">
                      <!-- color buttons -->
                    </div>
                  </div>

                  <!-- Size + quantity -->
                  <div class="grid grid-cols-[1.4fr_1fr] gap-2 items-end">
                    <div>
                      <p class="text-[11px] font-medium text-slate-600 mb-1">Size</p>
                      <select
                        id="config-size"
                        class="w-full rounded-lg border border-slate-300 bg-white px-2 py-1 text-xs"
                      ></select>
                    </div>
                    <div>
                      <p class="text-[11px] font-medium text-slate-600 mb-1">Quantity</p>
                      <input
                        id="config-qty"
                        type="number"
                        min="1"
                        value="1"
                        class="w-full rounded-lg border border-slate-300 bg-white px-2 py-1 text-xs"
                      />
                    </div>
                  </div>

                  <!-- Price summary -->
                  <div class="border-t border-slate-200 pt-3 mt-2">
                    <p class="text-[11px] text-slate-600 mb-1">Estimated price</p>
                    <p class="text-sm font-semibold text-slate-900" id="config-price">
                      —
                    </p>
                    <p class="text-[11px] text-slate-400 mt-1">
                      Exact total may adjust for print locations, complexity, and bulk pricing rules.
                    </p>
                  </div>
                </div>
              </div>

              <!-- CTA + debug -->
              <div class="border-t border-slate-200 pt-3 mt-1 space-y-2">
                <button
                  id="config-add-btn"
                  class="w-full rounded-full bg-black px-4 py-2 text-xs font-medium text-white hover:bg-zinc-800 disabled:opacity-50 disabled:cursor-not-allowed"
                  type="button"
                  disabled
                >
                  Add customized item (coming soon)
                </button>
                <p class="text-[11px] text-slate-400">
                  Next phase: this will create a Shopify cart with your selections and redirect to checkout.
                </p>

                <details class="mt-1 text-[11px] text-slate-500">
                  <summary class="cursor-pointer select-none text-[11px] text-slate-500">
                    Show debug info (dev only)
                  </summary>
                  <div class="mt-1 rounded border border-dashed border-slate-200 bg-slate-50 p-2 space-y-1">
                    <p><strong>Variant ID:</strong> <span id="debug-variant-id">None</span></p>
                    <p><strong>Color / Size / Qty:</strong> <span id="debug-selection">—</span></p>
                  </div>
                </details>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>

  <script>
    const state = {
      products: [],
      artworkFile: null,
      artworkPreviewUrl: null,
      artworkUpload: null,
      selectedProduct: null,
      selectedColor: null,
      selectedSize: null,
      quantity: 1,
      lastVariant: null,
    };

    const el = {
      artInput: document.getElementById('art-upload-input'),
      artPreview: document.getElementById('art-preview'),
      artPreviewPlaceholder: document.getElementById('art-preview-placeholder'),
      artStatus: document.getElementById('art-upload-status'),
      step2: document.getElementById('step-2'),
      step2Badge: document.getElementById('step-2-badge'),
      step2Circle: document.getElementById('step-2-circle'),
      products: document.getElementById('products'),
      productsStatus: document.getElementById('products-status'),
      configEmpty: document.getElementById('config-empty'),
      configContent: document.getElementById('config-content'),
      configProductImage: document.getElementById('config-product-image'),
      configArtOverlay: document.getElementById('config-art-overlay'),
      configProductTitle: document.getElementById('config-product-title'),
      configProductDescription: document.getElementById('config-product-description'),
      configColors: document.getElementById('config-colors'),
      configSize: document.getElementById('config-size'),
      configQty: document.getElementById('config-qty'),
      configPrice: document.getElementById('config-price'),
      configAddBtn: document.getElementById('config-add-btn'),
      debugVariantId: document.getElementById('debug-variant-id'),
      debugSelection: document.getElementById('debug-selection'),
    };

    // --- Step 1: artwork upload ---
    el.artInput.addEventListener('change', async (event) => {
        const file = event.target.files?.[0];
        if (!file) return;

        state.artworkFile = file;

        // Local preview
        if (state.artworkPreviewUrl) {
            URL.revokeObjectURL(state.artworkPreviewUrl);
        }
        state.artworkPreviewUrl = URL.createObjectURL(file);

        el.artPreview.src = state.artworkPreviewUrl;
        el.artPreview.classList.remove('hidden');
        el.artPreviewPlaceholder.classList.add('hidden');
        el.artStatus.textContent = `Artwork loaded: ${file.name} (uploading…)`;

        // enable Step 2 visually & functionally
        el.step2.classList.remove('opacity-40', 'pointer-events-none');
        el.step2Badge.textContent = 'Artwork ready · Configure your garments';
        el.step2Badge.classList.remove('bg-slate-200', 'text-slate-600');
        el.step2Badge.classList.add('bg-emerald-100', 'text-emerald-700');
        el.step2Circle.classList.remove('bg-slate-900/5', 'text-slate-500');
        el.step2Circle.classList.add('bg-emerald-600', 'text-white');

        // update overlay if a product is already selected
        if (state.selectedProduct) {
            el.configArtOverlay.src = state.artworkPreviewUrl;
            el.configArtOverlay.classList.remove('hidden');
        }

        // --- Upload to backend ---
        try {
            const formData = new FormData();
            formData.append('file', file);

            const res = await fetch('api.php/api/upload', {
            method: 'POST',
            body: formData,
            });

            const data = await res.json();

            if (!res.ok || !data.success) {
            console.error('Upload failed:', data);
            el.artStatus.textContent = `Artwork loaded locally, but upload failed. You can still preview, but backend doesn’t have the file.`;
            state.artworkUpload = null;
            return;
            }

            state.artworkUpload = data;
            el.artStatus.textContent = `Artwork uploaded: ${data.fileName} → ${data.storedFileName}`;
        } catch (err) {
            console.error(err);
            el.artStatus.textContent = `Artwork loaded locally, but upload failed (network error).`;
            state.artworkUpload = null;
        }
        });

    // --- Step 2: load products ---
    async function loadProducts() {
      el.productsStatus.textContent = 'Loading products…';

      try {
        const res = await fetch('api.php/api/products');
        const data = await res.json();

        if (!data.products || data.products.length === 0) {
          el.products.innerHTML = '';
          el.productsStatus.textContent = 'No products available.';
          return;
        }

        state.products = data.products;
        el.products.innerHTML = '';
        el.productsStatus.textContent = '';

        data.products.forEach((p) => {
          const card = document.createElement('button');
          card.type = 'button';
          card.className =
            'text-left rounded-xl border border-slate-200 bg-white p-3 shadow-sm flex flex-col gap-2 hover:border-black/70 hover:shadow-md transition';
          card.dataset.productId = p.id;

          const firstVariant = p.variants[0];

          card.innerHTML = `
            <div class="aspect-[4/3] bg-slate-100 rounded-lg mb-2 flex items-center justify-center overflow-hidden">
              ${
                p.image
                  ? `<img src="${p.image}" alt="${p.title}" class="object-contain max-h-full max-w-full" />`
                  : `<span class="text-[10px] text-slate-400">No image</span>`
              }
            </div>
            <h4 class="font-semibold text-xs line-clamp-2 text-slate-900">${p.title}</h4>
            <p class="text-[11px] text-slate-500 line-clamp-2">${p.description || ''}</p>
            <p class="text-[11px] text-slate-600 mt-1">
              From ${
                firstVariant
                  ? `${firstVariant.price} ${firstVariant.currency}`
                  : '—'
              }
            </p>
          `;

          card.addEventListener('click', () => {
            selectProduct(p.id);
          });

          el.products.appendChild(card);
        });
      } catch (err) {
        console.error(err);
        el.productsStatus.textContent = 'Failed to load products.';
      }
    }

    function selectProduct(productId) {
        const product = state.products.find((p) => p.id === productId);
        if (!product) return;

        const sameProduct =
            state.selectedProduct && state.selectedProduct.id === product.id;

        state.selectedProduct = product;

        // Derive color + size lists from variants
        const colors = Array.from(
            new Set(
            product.variants
                .map((v) => v.color)
                .filter((c) => !!c)
            )
        );
        const sizes = Array.from(
            new Set(
            product.variants
                .map((v) => v.size)
                .filter((s) => !!s)
            )
        );

        // Only set defaults when switching to a NEW product
        if (!sameProduct || !state.selectedColor || !colors.includes(state.selectedColor)) {
            state.selectedColor = colors[0] || null;
        }
        if (!sameProduct || !state.selectedSize || !sizes.includes(state.selectedSize)) {
            state.selectedSize = sizes[0] || null;
        }

        state.quantity = Number(el.configQty.value || 1) || 1;

        // Show config content
        el.configEmpty.classList.add('hidden');
        el.configContent.classList.remove('hidden');

        el.configProductImage.src = product.image || '';
        el.configProductTitle.textContent = product.title;
        el.configProductDescription.textContent = product.description || '';

        // artwork overlay
        if (state.artworkPreviewUrl) {
            el.configArtOverlay.src = state.artworkPreviewUrl;
            el.configArtOverlay.classList.remove('hidden');
        } else {
            el.configArtOverlay.classList.add('hidden');
        }

        // colors
        el.configColors.innerHTML = '';
        if (colors.length === 0) {
            const span = document.createElement('span');
            span.className = 'text-[11px] text-slate-400';
            span.textContent = 'No color options';
            el.configColors.appendChild(span);
        } else {
            colors.forEach((color) => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.textContent = color;

            const isActive = color === state.selectedColor;

            btn.className =
                'rounded-full border px-3 py-1 text-[11px] ' +
                (isActive
                ? 'bg-black text-white border-black'
                : 'bg-white text-slate-800 border-slate-300');

            btn.addEventListener('click', () => {
                state.selectedColor = color;
                // re-render buttons & update variant/price
                selectProduct(productId);
                updateVariantAndPrice();
            });

            el.configColors.appendChild(btn);
            });
        }

        // sizes
        el.configSize.innerHTML = '';
        if (sizes.length === 0) {
            const opt = document.createElement('option');
            opt.textContent = 'No sizes';
            el.configSize.appendChild(opt);
            el.configSize.disabled = true;
        } else {
            el.configSize.disabled = false;
            sizes.forEach((size) => {
            const opt = document.createElement('option');
            opt.value = size;
            opt.textContent = size;
            if (size === state.selectedSize) {
                opt.selected = true;
            }
            el.configSize.appendChild(opt);
            });
        }

        updateVariantAndPrice();
    }

    function updateVariantAndPrice() {
        if (!state.selectedProduct) return;

        if (el.configSize.value) state.selectedSize = el.configSize.value;
        state.quantity = Number(el.configQty.value || 1) || 1;

        const variant = state.selectedProduct.variants.find((v) => {
            const colorOk = !state.selectedColor || v.color === state.selectedColor;
            const sizeOk = !state.selectedSize || v.size === state.selectedSize;
            return colorOk && sizeOk;
        });

        state.lastVariant = variant || null; // 👈 store it

        if (!variant) {
            el.configPrice.textContent = 'Variant not available for this combination.';
            el.configAddBtn.disabled = true;
            el.debugVariantId.textContent = 'None';
            el.debugSelection.textContent = `${state.selectedColor || '—'} / ${
            state.selectedSize || '—'
            } · Qty ${state.quantity}`;
            return;
        }

        const unitPrice = parseFloat(variant.price || '0');
        const total = unitPrice * state.quantity;

        el.configPrice.textContent = `${total.toFixed(2)} ${variant.currency} (est.)`;
        el.configAddBtn.disabled = false;
        el.debugVariantId.textContent = variant.id;
        el.debugSelection.textContent = `${state.selectedColor || '—'} / ${
            state.selectedSize || '—'
        } · Qty ${state.quantity}`; 
    }

    // Events for size & quantity changes
    el.configSize.addEventListener('change', () => {
      updateVariantAndPrice();
    });

    el.configQty.addEventListener('input', () => {
      if (Number(el.configQty.value) < 1) {
        el.configQty.value = 1;
      }
      updateVariantAndPrice();
    });

    el.configAddBtn.addEventListener('click', async () => {
    if (!state.selectedProduct || !state.lastVariant) {
        alert('Please select a valid color/size combination.');
        return;
    }

    const payload = {
        variantId: state.lastVariant.id,
        quantity: state.quantity,
        custom: {
        productId: state.selectedProduct.id,
        productTitle: state.selectedProduct.title,
        color: state.selectedColor,
        size: state.selectedSize,
        artworkFileName: state.artworkFile ? state.artworkFile.name : null,
        artworkStoredFileName: state.artworkUpload ? state.artworkUpload.storedFileName : null,
        artworkUrl: state.artworkUpload ? state.artworkUpload.url : null,
        },
    };


    el.configAddBtn.disabled = true;
    el.configAddBtn.textContent = 'Creating cart…';

    try {
        const res = await fetch('api.php/api/cart/lines', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(payload),
        });

        const data = await res.json();

        if (!res.ok) {
        console.error('Cart error:', data);
        alert('Failed to create cart. Check console for details.');
        return;
        }

        if (data.checkoutUrl) {
        window.location.href = data.checkoutUrl;
        } else {
        console.error('No checkoutUrl in response:', data);
        alert('Cart created but no checkout URL returned.');
        }
    } catch (err) {
        console.error(err);
        alert('Something went wrong while creating the cart.');
    } finally {
        el.configAddBtn.disabled = false;
        el.configAddBtn.textContent = 'Add customized item (coming soon)';
    }
    });

    // Initial load
    loadProducts();
  </script>
</body>
</html>
