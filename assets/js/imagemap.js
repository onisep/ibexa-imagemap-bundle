import imageMapResize from 'image-map-resizer';

document.addEventListener('DOMContentLoaded', () => {
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
                        target.dispatchEvent(new Event('imagemap-popin-closed'));
                    })
                })
            }
        );
    }

    const scrollToEmbed = function (imageMap, e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (!target.hidden) {
            target.hidden = true;
            target.dispatchEvent(new Event('imagemap-embed-closed'));

            return;
        }

        imageMap.querySelectorAll('.imagemap__embeds__item:not([hidden])').forEach((item) => item.hidden = true);
        target.hidden = false;
        scroll({
            top: target.offsetTop,
            behavior: 'smooth',
        });
        target.dispatchEvent(new Event('imagemap-embed-opened'));
    }

    const openPopin = function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        target.classList.add('imagemap__popins__item-open');
        target.dispatchEvent(new Event('imagemap-popin-opened'));
    }

    imageMapResize('.imagemap map');

    document.querySelectorAll('.imagemap').forEach(initImageMap);

    if (location.hash && document.querySelector(location.hash + '.imagemap__embeds__item')) {
        document.querySelector(location.hash + '.imagemap__embeds__item').hidden = false;
    }
});
