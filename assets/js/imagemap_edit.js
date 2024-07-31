import ReactDOM from 'react-dom';
import { v4 as uuidv4 } from 'uuid';
import { SVG } from '@svgdotjs/svg.js';
import Sortable from 'sortablejs';
import { createPopper } from '@popperjs/core';
import '@interactjs/actions/drag';
import '@interactjs/actions/resize';
import '@interactjs/modifiers';
import '@interactjs/auto-start';
import interact from '@interactjs/interact';
import './svg.draw.js';

let drawing;

const keyPress = function (imageMap, e) {
  if (e.keyCode === 27 && drawing) {
    const drawingCp = drawing;
    drawing = null;
    drawingCp.draw('cancel');
    endDraw(imageMap);
  }

  if (e.keyCode === 13 && drawing?.node.tagName === 'polygon') {
    drawing.draw('done');
  }
}

const finishDraw = function (imageMap, draw) {
  if (!drawing) {
    return;
  }

  (imageMap.querySelector('.imagemap-add').onclick)();
  const area = imageMap.querySelector('.imagemap-area:last-child');
  const select = area.querySelector('select[data-prop="shape"]');
  const coords = area.querySelector('input[data-prop="coords"]');
  switch (drawing.node.tagName) {
    case 'rect':
      coords.value = `${drawing.x()},${drawing.y()},${drawing.width() + drawing.x() - 1},${drawing.height() + drawing.y() - 1}`;
      coords.value = coords.value.split(',').map((value) => parseInt(value)).join(',');
      select.value = 'rect';
      break;
    case 'circle':
      coords.value = `${drawing.cx()},${drawing.cy()},${drawing.radius()}`;
      coords.value = coords.value.split(',').map((value) => parseInt(value)).join(',');
      select.value = 'circle';
      break;
    case 'polygon':
      coords.value = drawing.node.getAttribute('points');
      coords.value = coords.value.split(' ').map(
          (pair) => pair.split(',').map((value) => parseInt(value)).join(',')
      ).join(' ');
      select.value = 'poly';
      break;
  }

  drawing.remove();
  drawing = null;
  recreateShape(area, draw);
  endDraw(imageMap);
}

const endDraw = function (imageMap) {
  imageMap.querySelector('.imagemap-draw-rect').disabled = false;
  imageMap.querySelector('.imagemap-draw-circle').disabled = false;
  imageMap.querySelector('.imagemap-draw-poly').disabled = false;
  imageMap.querySelector('.imagemap-help-rect').hidden = true;
  imageMap.querySelector('.imagemap-help-circle').hidden = true;
  imageMap.querySelector('.imagemap-help-poly').hidden = true;
}

const drawRect = function (imageMap, draw) {
  imageMap.querySelector('.imagemap-help-rect').hidden = false;
  drawShape(imageMap, draw.rect(), draw)
}

const drawCircle = function (imageMap, draw) {
  imageMap.querySelector('.imagemap-help-circle').hidden = false;
  drawShape(imageMap, draw.circle(), draw)
}

const drawPoly = function (imageMap, draw) {
  imageMap.querySelector('.imagemap-help-poly').hidden = false;
  drawShape(imageMap, draw.polygon(), draw);
}

const drawShape = function (imageMap, shape, draw) {
  imageMap.querySelector('.imagemap-draw-rect').disabled = true;
  imageMap.querySelector('.imagemap-draw-circle').disabled = true;
  imageMap.querySelector('.imagemap-draw-poly').disabled = true;
  shape.fill('grey').opacity('0.5').stroke('black').attr('stroke-dasharray', 4).draw();
  shape.on('drawstop', finishDraw.bind(null, imageMap, draw));
  drawing = shape;
}

const initDraw = function (image, draw) {
  draw.viewbox(0, 0, image.naturalWidth, image.naturalHeight).css({'position': 'absolute', 'width': `${image.width}px`, 'height': `${image.height}px`});
}

const initImageMap = function (imageMap) {
  const areas = imageMap.querySelector('.imagemap-areas');
  Sortable.create(areas, {
    onEnd: (evt) => updatePositions(evt.to),
    handle: '.imagemap-handle',
  });

  const prototype = areas.parentNode.dataset.prototype;
  const map = imageMap.querySelector('.imagemap-map');
  const image = imageMap.querySelector('.ibexa-field-edit-preview__media');
  const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
  const draw = SVG(svg);
  const parent = image.parentNode;
  parent.insertBefore(svg, parent.firstChild);
  imageMap.querySelector('.imagemap-add').onclick = addArea.bind(null, areas, prototype, map, draw);
  imageMap.querySelectorAll('.imagemap-area').forEach(
      (area) => initArea(area, map, draw)
  );

  image.useMap = '#' + map.name;
  parent.insertBefore(map, null);
  parent.style.position = 'relative';

  image.onload = initDraw.bind(null, image, draw);
  if (image.complete) {
    initDraw(image, draw);
  }

  document.addEventListener('keydown', keyPress.bind(null, imageMap));
  imageMap.querySelector('.imagemap-draw-rect').onclick = drawRect.bind(null, imageMap, draw);
  imageMap.querySelector('.imagemap-draw-circle').onclick = drawCircle.bind(null, imageMap, draw);
  imageMap.querySelector('.imagemap-draw-poly').onclick = drawPoly.bind(null, imageMap, draw);
}

const initArea = function (area, map, draw) {
  area.dataset.uuid = uuidv4();
  area.id = area.dataset.uuid;
  area.querySelector('.imagemap-remove').onclick = removeArea.bind(null, area, draw);
  const select = area.querySelector('.imagemap-relation-select');
  select.onchange = changeLinkType.bind(select, area);
  const source = area.querySelector('.imagemap-relation-source');
  const link = area.querySelector('.imagemap-relation-external-link');
  link.onchange = () => {
    source.value = link.value;
  }
  initUDW(area.querySelector('.imagemap-relation'));

  map.insertAdjacentHTML('beforeend', '<area target="_blank" />');
  const areaNode = map.querySelector('area:last-child');
  area.querySelectorAll('.imagemap-area-input').forEach(
      (input) => initInput(input, areaNode)
  );

  area.querySelectorAll('select[data-prop="shape"], input[data-prop="coords"]').forEach(
      (input) => {
        const currentOnChange = input.onchange;
        input.onchange = () => {
          currentOnChange();
          recreateShape(area, draw);
        }
      }
  );

  const target = area.querySelector('select.imagemap-area-target');
  target.onchange = () => area.querySelector('.imagemap-area-anchor').hidden = target.value !== 'embed';

  if (select.value === 'external') {
    target.querySelector('option[value="embed"]').hidden = true;
    target.querySelector('option[value="popin"]').hidden = true;
  }

  area.querySelectorAll('.ibexa-dropdown').forEach((dropdownContainer) => {
    const dropdownAlreadyInitialized = !!global.ibexa.helpers.objectInstances.getInstance(dropdownContainer);

    if (dropdownAlreadyInitialized) {
      return;
    }

    const dropdown = new global.ibexa.core.Dropdown({
      container: dropdownContainer,
    });

    dropdown.init();
  });

  recreateShape(area, draw);
}

const initInput = function (input, areaNode) {
  const prop = input.dataset.prop;
  input.onchange = () => areaNode[prop] = input.value;
  (input.onchange)();
}

const removeArea = function (area, draw) {
  const areas = area.parentNode;
  draw.findOne(`*[data-uuid="${area.dataset.uuid}"]`).remove();
  area.remove();
  updatePositions(areas);
}

const addArea = function (areas, prototype, map, draw) {
  areas.insertAdjacentHTML('beforeend', prototype.replace(/__name__/g, 100));
  updatePositions(areas);
  initArea(areas.querySelector('.imagemap-area:last-child'), map, draw);
}

const updatePositions = function (areas) {
  areas.querySelectorAll('.imagemap-area').forEach(
      (area, i) => {
        area.querySelectorAll('*[name]').forEach(
            (item) => item.name = item.name.replace(/\[map]\[\d+]/g, '[map]['+i+']')
        );
      }
  );
}

const changeLinkType = function (area) {
  const internalBlock = area.querySelector('.imagemap-relation-internal');
  const externalBlock = area.querySelector('.imagemap-relation-external');
  const source = area.querySelector('.imagemap-relation-source');
  const target = area.querySelector('.imagemap-area-target');
  source.value = '';
  if (this.value === 'internal') {
    internalBlock.hidden = false;
    internalBlock.querySelector('.imagemap-relation-name').textContent = '';
    externalBlock.hidden = true;

    if (target.value === 'embed' || target.value === 'popin') {
      target.value = '_blank';
    }

    target.querySelector('option[value="embed"]').hidden = true;
    target.querySelector('option[value="popin"]').hidden = true;
  } else {
    externalBlock.hidden = false;
    externalBlock.querySelector('.imagemap-relation-external-link').value = '';
    internalBlock.hidden = true;

    target.querySelector('option[value="embed"]').hidden = false;
    target.querySelector('option[value="popin"]').hidden = false;
  }
}

const confirmUDW = function (widget, container, e) {
  const source = widget.querySelector('.imagemap-relation-source');
  source.value = 'ezobject://'+e[0].ContentInfo.Content._id;
  widget.querySelector('.imagemap-relation-name').textContent = e[0].ContentInfo.Content.TranslatedName;
  ReactDOM.unmountComponentAtNode(container);
}

const showUDW = function (config, widget) {
  const container = document.querySelector('#react-udw');
  const token = document.querySelector('meta[name="CSRF-Token"]').content;
  const siteaccess = document.querySelector('meta[name="SiteAccess"]').content;
  ReactDOM.render(React.createElement(eZ.modules.UniversalDiscovery, {
    restInfo: {
      token,
      siteaccess,
    },
    onConfirm: confirmUDW.bind(null, widget, container),
    onCancel: () => ReactDOM.unmountComponentAtNode(container),
    ...config
  }), container);
}

const initUDW = function (widget) {
  const config = JSON.parse(widget.dataset.udwConfig);

  widget.querySelector('.imagemap-relation-browse').onclick = showUDW.bind(null, config, widget);
}

const onDragRect = function (node, e) {
  const x = parseInt(node.attributes.x.value) + e.delta.x;
  const y = parseInt(node.attributes.y.value) + e.delta.y;
  node.attributes.x.value = x;
  node.attributes.y.value = y;
}

const onEndRect = function (node, area) {
  const x = parseInt(node.attributes.x.value);
  const y = parseInt(node.attributes.y.value);
  const width = parseInt(node.attributes.width.value);
  const height = parseInt(node.attributes.height.value);
  area.querySelector('input[data-prop="coords"]').value = `${x},${y},${x + width - 1},${y + height - 1}`;
}

const onDragCircle = function (node, e) {
  const cx = parseInt(node.attributes.cx.value) + e.delta.x;
  const cy = parseInt(node.attributes.cy.value) + e.delta.y;
  node.attributes.cx.value = cx;
  node.attributes.cy.value = cy;
}

const onEndCircle = function (node, area) {
  const cx = parseInt(node.attributes.cx.value);
  const cy = parseInt(node.attributes.cy.value);
  const r = parseInt(node.attributes.r.value);
  area.querySelector('input[data-prop="coords"]').value = `${cx},${cy},${r}`;
}

const onDragPoly = function (node, e) {
  const newPoints = [];
  node.attributes.points.value.split(' ').forEach(
      (pair) => {
        const {0: x, 1: y} = pair.split(',');
        newPoints.push(`${parseInt(x) + e.delta.x},${parseInt(y) + e.delta.y}`);
      }
  );
  node.attributes.points.value = newPoints.join(' ');
}

const onEndPoly = function (node, area) {
  area.querySelector('input[data-prop="coords"]').value = node.attributes.points.value;
}

const onResizeRect = function (node, e) {
  const w = parseInt(node.attributes.width.value) + e.deltaRect.width;
  const h = parseInt(node.attributes.height.value) + e.deltaRect.height;
  node.attributes.width.value = w;
  node.attributes.height.value = h;
}

const onResizeCircle = function (node, e) {
  node.attributes.r.value = parseInt(node.attributes.r.value) + e.deltaRect.width;
}

const recreateShape = function (area, draw) {
  const oldNode = draw.findOne(`*[data-uuid="${area.dataset.uuid}"]`);
  if (oldNode) {
    oldNode.remove();
  }
  const oldTooltip = area.querySelector('.imagemap-popin')
  if (oldTooltip) {
    oldTooltip.remove();
  }

  const shape = area.querySelector('select[data-prop="shape"]').value;
  let coords = area.querySelector('input[data-prop="coords"]').value;
  let node, onDrag, onResize, onEnd;
  switch (shape) {
    case 'rect':
      coords = coords?.split(',');
      if (coords?.length < 4) {
        coords = [0, 0, 100, 100];
      }
      node = draw.rect(coords[2] - coords[0] + 1, coords[3] - coords[1] + 1).move(coords[0], coords[1]);
      onDrag = onDragRect;
      onResize = onResizeRect;
      onEnd = onEndRect;
      break;
    case 'circle':
      coords = coords?.split(',');
      if (coords?.length < 3) {
        coords = [50, 50, 50];
      }
      node = draw.circle(coords[2] * 2).cx(coords[0]).cy(coords[1]);
      onDrag = onDragCircle;
      onResize = onResizeCircle;
      onEnd = onEndCircle;
      break;
    case 'poly':
      node = draw.polygon(coords);
      onDrag = onDragPoly;
      onEnd = onEndPoly;
      break;
    default:
      return;
  }

  node.fill('grey').opacity('0.5').stroke('black').attr('stroke-dasharray', 4);
  node.attr('data-uuid', area.dataset.uuid);
  node.addClass('imagemap-shape');

  const tooltipProto = document.querySelector('.imagemap-popin-proto');
  const tooltip = tooltipProto.cloneNode(true);
  tooltip.classList.remove('imagemap-popin-proto');
  area.appendChild(tooltip);
  const link = tooltip.querySelector('a');
  link.addEventListener('blur', () => tooltip.hidden = true);
  link.addEventListener('click', () => tooltip.hidden = true);
  const popper = createPopper(node.node, tooltip, {
    placement: 'right',
  });
  node.click(() => {
    tooltip.hidden = false;
    link.href = `#${area.dataset.uuid}`;
    popper.update().then(() => link.focus());
  });

  interact(node.node).draggable({
    listeners: {
      move: onDrag.bind(null, node.node),
      end: onEnd.bind(null, node.node, area)
    },
    modifiers: [
      interact.modifiers.restrict({
        restrictRect: draw.node,
      })
    ]
  });
  if (onResize) {
    interact(node.node).resizable({
      edges: { bottom: shape === 'rect', right: true },
      listeners: {
        move: onResize.bind(null, node.node),
        end: onEnd.bind(null, node.node, area),
      }
    });
  }
}

document.querySelectorAll('.imagemap-edit').forEach(
    (imageMap) => initImageMap(imageMap)
);
