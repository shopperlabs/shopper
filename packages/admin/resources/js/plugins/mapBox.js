import mapboxgl from 'mapbox-gl'

/**
 * MapBox Alpine plugin.
 *
 * @param {string} element
 * @param {string} token
 * @returns {{container, init(): void, lng: number, zoom: number, lat: number, token}}
 */
export default (element, token) => ({
  container: element,
  token,
  lng: 9.9237,
  lat: 4.26397,
  zoom: 5,
  marker: null,

  init() {
    mapboxgl.accessToken = token
    const map = new mapboxgl.Map({
      container: this.container,
      style: 'mapbox://styles/mapbox/streets-v11',
      center: [this.lng, this.lat],
      zoom: this.zoom
    })

    // Add navigation control (the +/- zoom buttons)
    map.addControl(new mapboxgl.NavigationControl(), 'top-right')

    // Add geolocate control to the map.
    map.addControl(
      new mapboxgl.GeolocateControl({
        positionOptions: {
          enableHighAccuracy: true
        },
        trackUserLocation: true,
        showUserHeading: true
      })
    )

    // Create a new marker.
    this.marker = new mapboxgl.Marker({
      draggable: true
    })
      .setLngLat([this.lng, this.lat])
      .addTo(map)

    this.marker.on('drag', () => {
      let lngLat = this.marker.getLngLat()
      this.lng = lngLat.lng.toFixed(4)
      this.lat = lngLat.lat.toFixed(4)
    })
  }
})
