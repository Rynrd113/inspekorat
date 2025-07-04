// Test JavaScript syntax
let beritaFilterInstance = null;
let beritaData = [];

function filterBerita(filter) {
    console.log('filterBerita called with:', filter);
    if (beritaFilterInstance) {
        beritaFilterInstance.setFilter(filter);
    } else {
        console.error('BeritaFilter instance not found');
    }
}

function handleTerbaruClick(e) {
    console.log('handleTerbaruClick called');
    e.preventDefault();
    filterBerita('terbaru');
}

function handleTerpopulerClick(e) {
    console.log('handleTerpopulerClick called');
    e.preventDefault();
    filterBerita('terpopuler');
}

console.log('Syntax test passed!');
