const initImageMap = function (imageMap) {
  const img = imageMap.querySelector('img');
  const height = parseInt(imageMap.style.height);
  imageMap.querySelectorAll('area').forEach(
    initArea.bind(null, img, height)
  );
}

const initArea = function (img, height, area, idx) {
  const heightOffset = (idx + 1) * height;
  area.onmouseover = onMouse.bind(null, img, heightOffset, area);
  area.onmouseleave = onMouse.bind(null, img, -heightOffset, area);
}

const onMouse = function (img, heightOffset, area) {
  img.style.marginTop = (parseInt(img.style.marginTop ? img.style.marginTop : 0) - heightOffset) + 'px';
  const newCoords = [];
  area.coords.split(',').forEach(
    (coord, idx) => {
      if (idx % 2 === 0) {
        newCoords.push(coord);
        return;
      }

      newCoords.push(parseInt(coord) + heightOffset);
    }
  )
  area.coords = newCoords.join(',');
}

document.querySelectorAll('.image_map_node[data-animation="true"]').forEach(initImageMap);
