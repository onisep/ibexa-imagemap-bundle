import imageMapResize from 'image-map-resizer';

const initImageMap = function (imageMap) {
    imageMap.querySelectorAll('area[data-mode="embed"]').forEach(
        (area) => area.addEventListener('click', scrollToEmbed.bind(area, imageMap))
    );
}

const scrollToEmbed = function (imageMap, e) {
    e.preventDefault();
    imageMap.querySelectorAll('.imagemap__embeds__item').forEach((item) => item.hidden = true);
    const target = document.querySelector(this.getAttribute('href'));
    target.hidden = false;
    scroll({
        top: target.offsetTop,
        behavior: 'smooth',
    });
}

imageMapResize('.imagemap map');

document.querySelectorAll('.imagemap').forEach(initImageMap);

if (location.hash && document.querySelector(location.hash + '.imagemap__embeds__item')) {
    document.querySelector(location.hash + '.imagemap__embeds__item').hidden = false;
}
