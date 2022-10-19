import imageMapResize from 'image-map-resizer';

const initImageMap = function (imageMap) {
    imageMap.querySelectorAll('area[data-mode="embed"]').forEach(
        (area) => area.addEventListener('click', scrollToEmbed.bind(area, imageMap))
    );
    imageMap.querySelectorAll('area[data-mode="popin"]').forEach(
        (area) => {
            area.addEventListener('click', openPopin);
            const target = document.querySelector(area.getAttribute('href'));
            target.querySelectorAll('.imagemap__popins__item__exit').forEach((trigger) => {
                trigger.addEventListener('click', (e) => {
                    e.preventDefault();
                    target.classList.remove('imagemap__popins__item-open');
                })
            })
        }
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

const openPopin = function (e) {
    e.preventDefault();
    const target = document.querySelector(this.getAttribute('href'));
    target.classList.add('imagemap__popins__item-open');
}

imageMapResize('.imagemap map');

document.querySelectorAll('.imagemap').forEach(initImageMap);

if (location.hash && document.querySelector(location.hash + '.imagemap__embeds__item')) {
    document.querySelector(location.hash + '.imagemap__embeds__item').hidden = false;
}
