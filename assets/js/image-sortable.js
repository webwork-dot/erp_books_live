/**
 * Image Sortable and Main Image Selection
 * Reusable script for product image management
 */

(function() {
    'use strict';

    // Initialize image sortable functionality
    function initImageSortable(containerId, orderInputId, mainImageInputId) {
        const container = document.getElementById(containerId);
        const orderInput = document.getElementById(orderInputId);
        const mainImageInput = document.getElementById(mainImageInputId);
        
        if (!container) return;

        let imageFiles = [];
        let imagePreviews = [];
        let mainImageIndex = 0;

        // Handle file input change
        const fileInput = document.getElementById('images');
        
        if (fileInput) {
            fileInput.addEventListener('change', function(e) {
                const files = Array.from(e.target.files);
                // Clear previous previews if needed (for new uploads)
                if (imageFiles.length === 0) {
                    // Only clear if no existing images
                    const existingImages = container.querySelectorAll('.existing-image');
                    
                    if (existingImages.length === 0) {
                        container.innerHTML = '';
                        imagePreviews = [];
                    }
                }
                
                files.forEach((file, index) => {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const imageData = {
                                file: file,
                                preview: e.target.result,
                                index: imageFiles.length
                            };
                            console.log('Image loaded:', file.name, 'Preview length:', e.target.result.length);
                            imageFiles.push(imageData);
                            addImagePreview(imageData, imageFiles.length - 1);
                        };
                        reader.onerror = function() {
                            console.error('Error reading file:', file.name);
                        };
                        reader.readAsDataURL(file);
                    } else {
                        console.warn('Skipped non-image file:', file.name, file.type);
                    }
                });
            });
        }

        // Add image preview with drag and drop support
        function addImagePreview(imageData, index) {
            console.log('Adding image preview, index:', index);
            const imageWrapper = document.createElement('div');
            imageWrapper.className = 'image-preview-item';
            imageWrapper.draggable = true;
            imageWrapper.dataset.index = index;
            imageWrapper.style.cssText = 'position: relative; display: inline-block; margin: 3px; cursor: move; vertical-align: top; width: 120px; height: 120px; overflow: visible;';

            const img = document.createElement('img');
            img.src = imageData.preview;
            img.style.cssText = 'width: 120px; height: 120px; object-fit: cover; border: 2px solid #ddd; border-radius: 4px 4px 0 0; display: block;';
            img.alt = 'Preview';
            
            // Add error handler in case image fails to load
            img.onerror = function() {
                console.error('Failed to load image preview');
                this.style.display = 'none';
            };
            
            // Add load handler to verify image loaded
            img.onload = function() {
                console.log('Image preview rendered successfully');
            };

            // Create button container (always visible at bottom)
            const buttonContainer = document.createElement('div');
            buttonContainer.className = 'image-buttons';
            buttonContainer.style.cssText = 'position: absolute; bottom: 0; left: 0; right: 0; background: rgba(0,0,0,0.75); display: flex; gap: 2px; padding: 3px; border-radius: 0 0 4px 4px; z-index: 2;';

            const mainBtn = document.createElement('button');
            mainBtn.type = 'button';
            mainBtn.className = 'btn btn-sm set-main-btn';
            mainBtn.textContent = index === mainImageIndex ? 'Main' : 'Set Main';
            mainBtn.style.cssText = 'font-size: 10px; padding: 3px 6px; flex: 1; line-height: 1.2; border: none; white-space: nowrap;';
            if (index === mainImageIndex) {
                mainBtn.style.background = '#28a745';
                mainBtn.style.color = '#fff';
            } else {
                mainBtn.style.background = '#007bff';
                mainBtn.style.color = '#fff';
            }

            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'btn btn-sm btn-danger remove-image-btn';
            removeBtn.innerHTML = '<i class="isax isax-trash" style="font-size: 11px;"></i>';
            removeBtn.style.cssText = 'font-size: 10px; padding: 3px 6px; flex: 0 0 auto; line-height: 1.2; border: none; min-width: 28px;';

            buttonContainer.appendChild(mainBtn);
            buttonContainer.appendChild(removeBtn);

            imageWrapper.appendChild(img);
            imageWrapper.appendChild(buttonContainer);

            // Set as main image
            mainBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                setMainImage(index);
            });

            // Remove image
            removeBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                removeImage(index);
            });

            // Drag and drop handlers
            imageWrapper.addEventListener('dragstart', handleDragStart);
            imageWrapper.addEventListener('dragover', handleDragOver);
            imageWrapper.addEventListener('drop', handleDrop);
            imageWrapper.addEventListener('dragend', handleDragEnd);

            container.appendChild(imageWrapper);
            imagePreviews.push(imageWrapper);
            updateOrder();
        }

        // Drag and drop functions
        let draggedElement = null;

        function handleDragStart(e) {
            draggedElement = this;
            this.style.opacity = '0.5';
            e.dataTransfer.effectAllowed = 'move';
            e.dataTransfer.setData('text/html', this.innerHTML);
        }

        function handleDragOver(e) {
            if (e.preventDefault) {
                e.preventDefault();
            }
            e.dataTransfer.dropEffect = 'move';
            if (this !== draggedElement) {
                this.style.border = '2px dashed #007bff';
            }
            return false;
        }

        function handleDrop(e) {
            if (e.stopPropagation) {
                e.stopPropagation();
            }

            if (draggedElement !== this) {
                const allItems = Array.from(container.querySelectorAll('.image-preview-item'));
                const draggedIndex = allItems.indexOf(draggedElement);
                const targetIndex = allItems.indexOf(this);

                // Reorder arrays
                const draggedData = imageFiles[draggedIndex];
                imageFiles.splice(draggedIndex, 1);
                imageFiles.splice(targetIndex, 0, draggedData);

                // Update main image index if needed
                if (mainImageIndex === draggedIndex) {
                    mainImageIndex = targetIndex;
                } else if (mainImageIndex === targetIndex) {
                    mainImageIndex = draggedIndex;
                } else if (mainImageIndex > draggedIndex && mainImageIndex <= targetIndex) {
                    mainImageIndex--;
                } else if (mainImageIndex < draggedIndex && mainImageIndex >= targetIndex) {
                    mainImageIndex++;
                }

                // Rebuild previews
                container.innerHTML = '';
                imagePreviews = [];
                imageFiles.forEach((data, idx) => {
                    addImagePreview(data, idx);
                });
            }

            this.style.border = '';
            return false;
        }

        function handleDragEnd(e) {
            this.style.opacity = '1';
            const allItems = container.querySelectorAll('.image-preview-item');
            allItems.forEach(item => {
                item.style.border = '';
            });
        }

        // Set main image
        function setMainImage(index) {
            mainImageIndex = index;
            if (mainImageInput) {
                mainImageInput.value = index;
            }
            
            // Update all buttons
            const allMainBtns = container.querySelectorAll('.set-main-btn');
            allMainBtns.forEach((btn, idx) => {
                if (idx === index) {
                    btn.textContent = 'Main';
                    btn.style.background = '#28a745';
                    btn.style.color = '#fff';
                } else {
                    btn.textContent = 'Set Main';
                    btn.style.background = '#007bff';
                    btn.style.color = '#fff';
                }
            });
        }

        // Remove image
        function removeImage(index) {
            if (confirm('Are you sure you want to remove this image?')) {
                imageFiles.splice(index, 1);
                
                // Update main image index
                if (index === mainImageIndex) {
                    mainImageIndex = 0; // Set first image as main
                } else if (index < mainImageIndex) {
                    mainImageIndex--;
                }

                // Rebuild previews
                container.innerHTML = '';
                imagePreviews = [];
                imageFiles.forEach((data, idx) => {
                    addImagePreview(data, idx);
                });

                // Update file input
                updateFileInput();
            }
        }

        // Update file input with remaining files
        function updateFileInput() {
            const dt = new DataTransfer();
            imageFiles.forEach(data => {
                dt.items.add(data.file);
            });
            if (fileInput) {
                fileInput.files = dt.files;
            }
        }

        // Update order hidden input
        function updateOrder() {
            if (orderInput) {
                const order = imageFiles.map((_, index) => index).join(',');
                orderInput.value = order;
            }
        }

        // Initialize main image
        if (mainImageInput) {
            mainImageInput.value = '0';
        }
    }

    // Auto-initialize when DOM is ready
    function initializeImageSortable() {
        const container = document.getElementById('image-preview');
        if (container) {
            // Don't initialize if there are existing images (edit form handles those)
            const existingImages = container.querySelectorAll('.existing-image');
            if (existingImages.length > 0) {
                console.log('Existing images found, skipping image-sortable initialization');
                return;
            }
            
            // Check if this is an edit form (has main_image_id) or add form (has main_image_index)
            const mainImageInputId = document.getElementById('main_image_id') ? 'main_image_id' : 'main_image_index';
            initImageSortable('image-preview', 'image_order', mainImageInputId);
        }
    }
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(initializeImageSortable, 100);
        });
    } else {
        // DOM already loaded, but wait a bit for other scripts
        setTimeout(initializeImageSortable, 100);
    }

    // Export for manual initialization
    window.initImageSortable = initImageSortable;
})();

