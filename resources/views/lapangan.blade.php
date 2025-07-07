<!DOCTYPE html>
<html>

<head>
    <title>Daftar Fields dengan Thumbnail</title>
    <style>
        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        img {
            width: 100px;
            height: 70px;
            object-fit: cover;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <h1>Daftar Fields</h1>
    <div id="fields-list">Loading...</div>

    <script>
        // Base URL untuk gambar yang disimpan di storage Laravel
        const baseStorageUrl = 'http://localhost:8000/storage/';

        fetch('http://localhost:8000/api/v1/fields')
            .then(response => response.json())
            .then(data => {
                const fields = data.data || data;

                if (!fields || fields.length === 0) {
                    document.getElementById('fields-list').innerText = 'Tidak ada data fields.';
                    return;
                }

                let html = '<ul>';
                fields.forEach(field => {
                    const name = field.name || 'Nama tidak tersedia';
                    const desc = field.description || '-';

                    // Thumbnail utama
                    let thumbnail = field.thumbnail || '';
                    if (thumbnail) {
                        if (!thumbnail.startsWith('http://') && !thumbnail.startsWith('https://')) {
                            thumbnail = baseStorageUrl + thumbnail.replace(/^\/+/, '');
                        }
                    } else {
                        thumbnail = 'https://via.placeholder.com/100x70?text=No+Image';
                    }

                    // Detail photos array
                    let detailPhotosHtml = '';
                    if (Array.isArray(field.detail_photos)) {
                        field.detail_photos.forEach(photoPath => {
                            let fullPhotoPath = photoPath;
                            if (!photoPath.startsWith('http://') && !photoPath.startsWith('https://')) {
                                fullPhotoPath = baseStorageUrl + photoPath.replace(/^\/+/, '');
                            }

                            detailPhotosHtml += `
                <img src="${fullPhotoPath}" alt="Detail Foto ${name}" style="width: 80px; margin-right: 5px;" />
            `;
                        });
                    }

                    html += `
        <li style="margin-bottom: 20px;">
            <img src="${thumbnail}" alt="Thumbnail ${name}" style="width: 100px;" />
            <div>
                <strong>${name}</strong><br />
                <small>${desc}</small><br />
                <div style="margin-top: 5px;">${detailPhotosHtml}</div>
            </div>
        </li>
    `;
                });

                html += '</ul>';

                document.getElementById('fields-list').innerHTML = html;
            })
            .catch(error => {
                console.error('Error fetching fields:', error);
                document.getElementById('fields-list').innerText = 'Gagal mengambil data dari API.';
            });
    </script>
</body>

</html>
