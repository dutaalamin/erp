import React, { useState } from 'react';
import { 
  LayoutDashboard, 
  ShoppingCart, 
  Users, 
  Package, 
  Settings,
  DollarSign,
  Activity,
  Plus,
  Search,
  Filter,
  Save,
  Globe,
  Bell,
  ChevronRight
} from 'lucide-react';

const mockSalesData = [
  { so_number: 'SO-2023-0001', customer: 'PT. Maju Mundur', amount: 'Rp 45.000.000', status: 'delivered', date: '2026-07-14' },
  { so_number: 'SO-2023-0002', customer: 'CV. Karya Abadi', amount: 'Rp 12.200.000', status: 'draft', date: '2026-07-15' },
  { so_number: 'SO-2023-0003', customer: 'PT. Teknologi Nusantara', amount: 'Rp 89.500.000', status: 'confirmed', date: '2026-07-15' },
  { so_number: 'SO-2023-0004', customer: 'Toko Sumber Rejeki', amount: 'Rp 2.100.000', status: 'cancelled', date: '2026-07-16' },
  { so_number: 'SO-2023-0005', customer: 'PT. Angin Ribut', amount: 'Rp 15.000.000', status: 'in_progress', date: '2026-07-17' },
];

const mockCustomers = [
  { id: 'CUST-001', name: 'PT. Maju Mundur', email: 'contact@majumundur.co.id', phone: '+62 812-3456-7890', status: 'Active' },
  { id: 'CUST-002', name: 'CV. Karya Abadi', email: 'info@karyaabadi.com', phone: '+62 813-9876-5432', status: 'Active' },
  { id: 'CUST-003', name: 'PT. Teknologi Nusantara', email: 'hello@teknus.id', phone: '+62 811-1234-5678', status: 'Inactive' },
  { id: 'CUST-004', name: 'Toko Sumber Rejeki', email: 'sumberrejeki@gmail.com', phone: '+62 856-7777-8888', status: 'Active' },
];

const mockProducts = [
  { id: 'PRD-001', name: 'Semen Portland 50kg', category: 'Material Bangunan', price: 'Rp 65.000', stock: 450 },
  { id: 'PRD-002', name: 'Besi Beton 10mm', category: 'Besi & Baja', price: 'Rp 75.000', stock: 1200 },
  { id: 'PRD-003', name: 'Cat Tembok Putih 25kg', category: 'Cat', price: 'Rp 850.000', stock: 32 },
  { id: 'PRD-004', name: 'Keramik Lantai 40x40', category: 'Keramik', price: 'Rp 55.000', stock: 180 },
];

function App() {
  const [activeMenu, setActiveMenu] = useState('Dashboard');
  const [searchQuery, setSearchQuery] = useState('');

  const menuItems = [
    { name: 'Dashboard', icon: <LayoutDashboard size={20} /> },
    { name: 'Sales Orders', icon: <ShoppingCart size={20} /> },
    { name: 'Customers', icon: <Users size={20} /> },
    { name: 'Products', icon: <Package size={20} /> },
    { name: 'Settings', icon: <Settings size={20} /> },
  ];

  const filteredSales = mockSalesData.filter(sale => 
    sale.customer.toLowerCase().includes(searchQuery.toLowerCase()) || 
    sale.so_number.toLowerCase().includes(searchQuery.toLowerCase())
  );

  return (
    <div className="app-container">
      {/* Sidebar */}
      <aside className="sidebar">
        <div className="sidebar-header">
          <div className="sidebar-header-icon">
            <Globe size={24} color="white" />
          </div>
          ERP System
        </div>
        <ul className="sidebar-menu">
          <p style={{fontSize: '12px', fontWeight: 600, color: 'rgba(255,255,255,0.4)', textTransform: 'uppercase', letterSpacing: '1px', margin: '10px 0 5px 20px'}}>Menu Utama</p>
          {menuItems.map((item) => (
            <li 
              key={item.name} 
              className={activeMenu === item.name ? 'active' : ''}
              onClick={() => {
                setActiveMenu(item.name);
                setSearchQuery('');
              }}
            >
              {item.icon}
              <span style={{flex: 1}}>{item.name}</span>
              {activeMenu === item.name && <ChevronRight size={16} />}
            </li>
          ))}
        </ul>
      </aside>

      {/* Main Content */}
      <main className="main-content animate-fade-in">
        <header className="header">
          <h1>{activeMenu}</h1>
          <div style={{display: 'flex', gap: '20px', alignItems: 'center'}}>
            <div style={{position: 'relative', cursor: 'pointer', color: '#64748b'}}>
              <Bell size={24} />
              <div style={{position: 'absolute', top: '-2px', right: '-2px', width: '10px', height: '10px', backgroundColor: '#ef4444', borderRadius: '50%', border: '2px solid var(--bg-color)'}}></div>
            </div>
            <div className="user-profile">
              <div className="avatar">AD</div>
              <span style={{fontWeight: 600, fontSize: '14px'}}>Admin User</span>
            </div>
          </div>
        </header>

        {activeMenu === 'Dashboard' && (
          <div className="animate-fade-in">
            <div className="stats-grid">
              <div className="stat-card">
                <div className="stat-icon icon-blue"><DollarSign size={28} /></div>
                <div className="stat-details">
                  <h3>Total Revenue</h3>
                  <p>Rp 1.24B</p>
                </div>
              </div>
              <div className="stat-card">
                <div className="stat-icon icon-purple"><ShoppingCart size={28} /></div>
                <div className="stat-details">
                  <h3>Sales Orders</h3>
                  <p>1,284</p>
                </div>
              </div>
              <div className="stat-card">
                <div className="stat-icon icon-pink"><Users size={28} /></div>
                <div className="stat-details">
                  <h3>Active Customers</h3>
                  <p>342</p>
                </div>
              </div>
              <div className="stat-card">
                <div className="stat-icon icon-green"><Activity size={28} /></div>
                <div className="stat-details">
                  <h3>Growth (YTD)</h3>
                  <p>+12.5%</p>
                </div>
              </div>
            </div>

            <div className="data-table-container">
              <h2 style={{marginBottom: '20px', fontSize: '20px', fontWeight: 700}}>Recent Sales Orders</h2>
              <table className="data-table">
                <thead>
                  <tr>
                    <th>SO Number</th>
                    <th>Customer Name</th>
                    <th>Order Date</th>
                    <th>Amount (Rp)</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  {mockSalesData.slice(0,4).map((order) => (
                    <tr key={order.so_number}>
                      <td style={{fontWeight: 600, color: 'var(--primary)'}}>{order.so_number}</td>
                      <td style={{fontWeight: 500}}>{order.customer}</td>
                      <td>{order.date}</td>
                      <td style={{fontWeight: 600}}>{order.amount}</td>
                      <td>
                        <span className={`status-badge status-${order.status}`}>
                          {order.status.replace('_', ' ').toUpperCase()}
                        </span>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        )}

        {activeMenu === 'Sales Orders' && (
          <div className="data-table-container animate-fade-in">
            <div className="table-header-controls">
              <div style={{ display: 'flex', gap: '15px', alignItems: 'center' }}>
                <div className="search-box">
                  <Search size={18} style={{ position: 'absolute', left: '16px', top: '50%', transform: 'translateY(-50%)', color: '#94a3b8' }} />
                  <input 
                    type="text" 
                    placeholder="Search by SO Number or Customer..." 
                    value={searchQuery}
                    onChange={(e) => setSearchQuery(e.target.value)}
                  />
                </div>
                <button className="btn-secondary">
                  <Filter size={16} /> Filter
                </button>
              </div>
              <button className="btn-primary">
                <Plus size={18} /> New Sales Order
              </button>
            </div>
            <table className="data-table">
                <thead>
                  <tr>
                    <th>SO Number</th>
                    <th>Customer Name</th>
                    <th>Order Date</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  {filteredSales.map((order) => (
                    <tr key={order.so_number}>
                      <td style={{fontWeight: 600, color: 'var(--primary)'}}>{order.so_number}</td>
                      <td style={{fontWeight: 500}}>{order.customer}</td>
                      <td>{order.date}</td>
                      <td style={{fontWeight: 600}}>{order.amount}</td>
                      <td>
                        <span className={`status-badge status-${order.status}`}>
                          {order.status.replace('_', ' ').toUpperCase()}
                        </span>
                      </td>
                      <td>
                        <button className="action-btn">Edit</button>
                      </td>
                    </tr>
                  ))}
                  {filteredSales.length === 0 && (
                    <tr>
                      <td colSpan="6" style={{textAlign: 'center', padding: '40px', color: 'var(--text-muted)'}}>No sales orders found matching your search.</td>
                    </tr>
                  )}
                </tbody>
              </table>
          </div>
        )}

        {activeMenu === 'Customers' && (
          <div className="data-table-container animate-fade-in">
            <div className="table-header-controls">
              <div className="search-box">
                <Search size={18} style={{ position: 'absolute', left: '16px', top: '50%', transform: 'translateY(-50%)', color: '#94a3b8' }} />
                <input type="text" placeholder="Search customers..." />
              </div>
              <button className="btn-primary">
                <Plus size={18} /> Add Customer
              </button>
            </div>
            <table className="data-table">
              <thead>
                <tr>
                  <th>Customer ID</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Phone</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                {mockCustomers.map((cust) => (
                  <tr key={cust.id}>
                    <td style={{fontWeight: 600, color: 'var(--primary)'}}>{cust.id}</td>
                    <td style={{fontWeight: 500}}>{cust.name}</td>
                    <td>{cust.email}</td>
                    <td>{cust.phone}</td>
                    <td>
                      <span className={`status-badge status-${cust.status.toLowerCase()}`}>
                        {cust.status.toUpperCase()}
                      </span>
                    </td>
                    <td>
                      <button className="action-btn">Edit</button>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        )}

        {activeMenu === 'Products' && (
          <div className="data-table-container animate-fade-in">
            <div className="table-header-controls">
              <div className="search-box">
                <Search size={18} style={{ position: 'absolute', left: '16px', top: '50%', transform: 'translateY(-50%)', color: '#94a3b8' }} />
                <input type="text" placeholder="Search products..." />
              </div>
              <button className="btn-primary">
                <Plus size={18} /> Add Product
              </button>
            </div>
            <table className="data-table">
              <thead>
                <tr>
                  <th>Product ID</th>
                  <th>Product Name</th>
                  <th>Category</th>
                  <th>Price</th>
                  <th>Stock</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                {mockProducts.map((prod) => (
                  <tr key={prod.id}>
                    <td style={{fontWeight: 600, color: 'var(--primary)'}}>{prod.id}</td>
                    <td style={{fontWeight: 500}}>{prod.name}</td>
                    <td>{prod.category}</td>
                    <td style={{fontWeight: 600}}>{prod.price}</td>
                    <td>
                      <span className={`status-badge status-${prod.stock > 200 ? 'delivered' : 'pending'}`}>
                        {prod.stock} Unit
                      </span>
                    </td>
                    <td>
                      <button className="action-btn">Edit</button>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        )}

        {activeMenu === 'Settings' && (
          <div className="data-table-container animate-fade-in" style={{maxWidth: '800px'}}>
            <h2 style={{marginBottom: '30px', paddingBottom: '15px', borderBottom: '1px solid var(--border)', fontSize: '20px'}}>General Settings</h2>
            
            <div style={{display: 'flex', flexDirection: 'column', gap: '24px'}}>
              <div style={{display: 'flex', flexDirection: 'column', gap: '8px'}}>
                <label style={{fontWeight: 600, color: 'var(--text-main)', fontSize: '14px'}}>Company Name</label>
                <input type="text" defaultValue="PT. Maju Bersama (Portfolio)" className="search-box" style={{width: '100%', padding: '12px 16px', borderRadius: '12px', border: '1px solid var(--border)', outline: 'none', background: '#f8fafc'}} />
              </div>
              
              <div style={{display: 'flex', flexDirection: 'column', gap: '8px'}}>
                <label style={{fontWeight: 600, color: 'var(--text-main)', fontSize: '14px'}}>Support Email</label>
                <input type="email" defaultValue="admin@majubersama.com" style={{width: '100%', padding: '12px 16px', borderRadius: '12px', border: '1px solid var(--border)', outline: 'none', background: '#f8fafc'}} />
              </div>

              <div style={{display: 'flex', flexDirection: 'column', gap: '8px'}}>
                <label style={{fontWeight: 600, color: 'var(--text-main)', fontSize: '14px'}}>Timezone</label>
                <select style={{width: '100%', padding: '12px 16px', borderRadius: '12px', border: '1px solid var(--border)', outline: 'none', background: 'white'}}>
                  <option>Asia/Jakarta (GMT+7)</option>
                  <option>UTC (GMT+0)</option>
                  <option>America/New_York (GMT-5)</option>
                </select>
              </div>

              <div style={{display: 'flex', flexDirection: 'column', gap: '10px'}}>
                <label style={{fontWeight: 600, color: 'var(--text-main)', fontSize: '14px'}}>Theme Preference</label>
                <div style={{display: 'flex', gap: '24px'}}>
                  <label style={{display: 'flex', alignItems: 'center', gap: '8px', fontSize: '15px', cursor: 'pointer'}}><input type="radio" name="theme" defaultChecked /> Light Theme</label>
                  <label style={{display: 'flex', alignItems: 'center', gap: '8px', fontSize: '15px', cursor: 'pointer'}}><input type="radio" name="theme" /> Dark Theme</label>
                </div>
              </div>

              <div style={{marginTop: '20px'}}>
                <button className="btn-primary">
                  <Save size={18} /> Save Configurations
                </button>
              </div>
            </div>
          </div>
        )}
      </main>
    </div>
  );
}

export default App;
