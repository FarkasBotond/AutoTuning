const API_BASE_URL = 'http://localhost/api'

const buildApiError = async (response, fallbackMessage) => {
  let payload = null

  try {
    payload = await response.json()
  } catch {
    payload = null
  }

  const error = new Error(
    payload?.message ||
      payload?.data?.message ||
      fallbackMessage ||
      `HTTP error! status: ${response.status}`
  )

  error.status = response.status
  error.data = payload
  return error
}

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
    throw await buildApiError(response, 'Sikertelen bejelentkezés')
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
    throw await buildApiError(response, 'Sikertelen kijelentkezés')
  }

  return response.json()
}

export const updateProfileEmail = async (data, token) => {
  return fetchWithToken('/profile/email', { method: 'PUT', body: JSON.stringify(data) }, token)
}

export const updateProfilePassword = async (data, token) => {
  return fetchWithToken('/profile/password', { method: 'PUT', body: JSON.stringify(data) }, token)
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
    throw await buildApiError(response)
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

// Tunings API
export const fetchTuningProducts = async (filters = {}) => {
  const params = new URLSearchParams()

  if (filters.car_model_id) {
    params.append('car_model_id', filters.car_model_id)
  }

  if (filters.service_category_id) {
    params.append('service_category_id', filters.service_category_id)
  }

  const query = params.toString()
  const endpoint = query ? `/tuning-products?${query}` : '/tuning-products'

  const response = await fetch(`${API_BASE_URL}${endpoint}`, {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json'
    },
    credentials: 'include'
  })

  if (!response.ok) {
    throw await buildApiError(response, 'Failed to fetch tuning products')
  }

  return response.json()
}

export const fetchServiceCategories = async () => {
  const response = await fetch(`${API_BASE_URL}/service-categories`, {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json'
    },
    credentials: 'include'
  })

  if (!response.ok) {
    throw await buildApiError(response, 'Nem sikerült betölteni a kategóriákat')
  }

  return response.json()
}

export const fetchTuningCompanies = async () => {
  const response = await fetch(`${API_BASE_URL}/tuning-companies`, {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json'
    },
    credentials: 'include'
  })

  if (!response.ok) {
    throw await buildApiError(response, 'Nem sikerült betölteni a tuning cégeket')
  }

  return response.json()
}

export const fetchTuningCompany = async (id) => {
  const response = await fetch(`${API_BASE_URL}/tuning-companies/${id}`, {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json'
    },
    credentials: 'include'
  })

  if (!response.ok) {
    throw await buildApiError(response, 'Nem sikerült betölteni a tuning céget')
  }

  return response.json()
}