const API_BASE_URL = 'http://localhost/api'

export const login = async (email, password) => {
  const response = await fetch(`${API_BASE_URL}/login`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json'
    },
    credentials: 'include',
    body: JSON.stringify({
      email,
      password
    })
  })

  if (!response.ok) {
    throw new Error(`HTTP error! status: ${response.status}`)
  }

  return response.json()
}

export const logout = async (token) => {
  const response = await fetch(`${API_BASE_URL}/logout`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'Authorization': `Bearer ${token}`
    },
    credentials: 'include'
  })

  if (!response.ok) {
    throw new Error(`HTTP error! status: ${response.status}`)
  }

  return response.json()
}

export const fetchWithToken = async (endpoint, options = {}, token) => {
  const response = await fetch(`${API_BASE_URL}${endpoint}`, {
    ...options,
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'Authorization': `Bearer ${token}`,
      ...options.headers
    },
    credentials: 'include'
  })

  if (!response.ok) {
    throw new Error(`HTTP error! status: ${response.status}`)
  }

  return response.json()
}

// Car Brands API
export const fetchBrands = async (token) => {
  return fetchWithToken('/car-brands', { method: 'GET' }, token)
}

export const fetchBrand = async (id, token) => {
  return fetchWithToken(`/car-brands/${id}`, { method: 'GET' }, token)
}

export const createBrand = async (data, token) => {
  return fetchWithToken('/car-brands', { method: 'POST', body: JSON.stringify(data) }, token)
}

export const updateBrand = async (id, data, token) => {
  return fetchWithToken(`/car-brands/${id}`, { method: 'PUT', body: JSON.stringify(data) }, token)
}

export const deleteBrand = async (id, token) => {
  return fetchWithToken(`/car-brands/${id}`, { method: 'DELETE' }, token)
}

// Car Models API
export const fetchModels = async (token) => {
  return fetchWithToken('/car-models', { method: 'GET' }, token)
}

export const fetchModel = async (id, token) => {
  return fetchWithToken(`/car-models/${id}`, { method: 'GET' }, token)
}

export const createModel = async (data, token) => {
  return fetchWithToken('/car-models', { method: 'POST', body: JSON.stringify(data) }, token)
}

export const updateModel = async (id, data, token) => {
  return fetchWithToken(`/car-models/${id}`, { method: 'PUT', body: JSON.stringify(data) }, token)
}

export const deleteModel = async (id, token) => {
  return fetchWithToken(`/car-models/${id}`, { method: 'DELETE' }, token)
}
