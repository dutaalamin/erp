import React, { useState } from 'react';
import { 
  LayoutDashboard, 
  ShoppingCart, 
  Users, 
  Package, 
  Settings,
  TrendingUp,
  DollarSign,
  Activity,
  Plus,
  Search,
  Filter,
  Save
} from 'lucide-react';

const mockSalesData = [
  { id: 'SO-1029', customer: 'Acme Corp', amount: '$4,500.00', status: 'Completed', date: '2026-07-14' },
  { id: 'SO-1030', customer: 'Global Tech', amount: '$12,200.00', status: 'Pending', date: '2026-07-15' },
  { id: 'SO-1031', customer: 'Stark Industries', amount: '$8,950.00', status: 'Completed', date: '2026-07-15' },
  { id: 'SO-1032', customer: 'Wayne Enterprises', amount: '$2,100.00', status: 'Cancelled', date: '2026-07-16' },
  { id: 'SO-1033', customer: 'Cyberdyne Systems', amount: '$15,000.00', status: 'Pending', date: '2026-07-17' },
  { id: 'SO-1034', customer: 'Oscorp', amount: '$3,450.00', status: 'Completed', date: '2026-07-17' },
];

const mockCustomers = [
  { id: 'CUST-001', name: 'Acme Corp', email: 'contact@acme.com', phone: '+1 555-0100', status: 'Active' },
  { id: 'CUST-002', name: 'Global Tech', email: 'info@globaltech.io', phone: '+1 555-0101', status: 'Active' },
  { id: 'CUST-003', name: 'Stark Industries', email: 'hello@stark.com', phone: '+1 555-0102', status: 'Inactive' },
  { id: 'CUST-004', name: 'Wayne Enterprises', email: 'bruce@wayne.com', phone: '+1 555-0103', status: 'Active' },
];

const mockProducts = [
  { id: 'PRD-001', name: 'Laptop Pro X', category: 'Electronics', price: '$1,299.00', stock: 45 },
  { id: 'PRD-002', name: 'Wireless Mouse', category: 'Accessories', price: '$49.99', stock: 120 },
  { id: 'PRD-003', name: 'Mechanical Keyboard', category: 'Accessories', price: '$159.00', stock: 32 },
  { id: 'PRD-004', name: '4K Monitor', category: 'Electronics', price: '$399.00', stock: 18 },
];

function App() {
  const [activeMenu, setActiveMenu] = useState('Dashboard');
  const [searchQuery, setSearchQuery] = useState('');

  const menuItems = [
    { name: 'Dashboard', icon: <LayoutDashboard size={20} /> },
    { name: 'Sales', icon: <ShoppingCart size={20} /> },
    { name: 'Customers', icon: <Users size={20} /> },
    { name: 'Products', icon: <Package size={20} /> },
    { name: 'Settings', icon: <Settings size={20} /> },
  ];

  const filteredSales = mockSalesData.filter(sale => 
    sale.customer.toLowerCase().includes(searchQuery.toLowerCase()) || 
    sale.id.toLowerCase().includes(searchQuery.toLowerCase())
  );

  return (
    <div className="app-container">
      {/* Sidebar */}
      <aside className="sidebar">
        <div className="sidebar-header">
          ERP System
        </div>
        <ul className="sidebar-menu">
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
              <span>{item.name}</span>
            </li>
          ))}
        </ul>
      </aside>

      {/* Main Content */}
      <main className="main-content">
        <header className="header">
          <h1>{activeMenu}</h1>
          <div className="user-profile">
            <span style={{fontWeight: 500}}>Admin User</span>
          </div>
        </header>

        {activeMenu === 'Dashboard' && (
          <>
            <div className="stats-grid">
              <div className="stat-card">
                <div className="stat-icon"><DollarSign size={24} /></div>
                <div className="stat-details">
                  <h3>Total Revenue</h3>
                  <p>$124,500</p>
                </div>
              </div>
              <div className="stat-card">
                <div className="stat-icon"><ShoppingCart size={24} /></div>
                <div className="stat-details">
                  <h3>Total Sales</h3>
                  <p>1,284</p>
                </div>
              </div>
              <div className="stat-card">
                <div className="stat-icon"><Users size={24} /></div>
                <div className="stat-details">
                  <h3>Active Customers</h3>
                  <p>342</p>
                </div>
              </div>
              <div className="stat-card">
                <div className="stat-icon"><Activity size={24} /></div>
                <div className="stat-details">
                  <h3>Growth</h3>
                  <p>+12.5%</p>
                </div>
              </div>
            </div>

            <div className="data-table-container">
              <h2 style={{marginBottom: '15px'}}>Recent Sales Orders</h2>
              <table className="data-table">
                <thead>
                  <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  {mockSalesData.slice(0,4).map((order) => (
                    <tr key={order.id}>
                      <td style={{fontWeight: 500, color: '#3b82f6'}}>{order.id}</td>
                      <td>{order.customer}</td>
                      <td>{order.date}</td>
                      <td>{order.amount}</td>
                      <td>
                        <span className={`status-badge status-${order.status.toLowerCase()}`}>
                          {order.status}
                        </span>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </>
        )}

        {activeMenu === 'Sales' && (
          <div className="data-table-container">
            <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: '20px' }}>
              <div style={{ display: 'flex', gap: '10px', alignItems: 'center' }}>
                <div style={{ position: 'relative' }}>
                  <Search size={18} style={{ position: 'absolute', left: '10px', top: '50%', transform: 'translateY(-50%)', color: '#6b7280' }} />
                  <input 
                    type="text" 
                    placeholder="Search sales..." 
                    value={searchQuery}
                    onChange={(e) => setSearchQuery(e.target.value)}
                    style={{ padding: '8px 12px 8px 35px', borderRadius: '6px', border: '1px solid #d1d5db', outline: 'none' }}
                  />
                </div>
                <button style={{ padding: '8px 12px', borderRadius: '6px', border: '1px solid #d1d5db', background: 'white', display: 'flex', alignItems: 'center', gap: '5px', cursor: 'pointer' }}>
                  <Filter size={16} /> Filter
                </button>
              </div>
              <button style={{ padding: '8px 16px', borderRadius: '6px', border: 'none', background: '#3b82f6', color: 'white', display: 'flex', alignItems: 'center', gap: '5px', cursor: 'pointer', fontWeight: 500 }}>
                <Plus size={18} /> New Sales Order
              </button>
            </div>
            <table className="data-table">
                <thead>
                  <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  {filteredSales.map((order) => (
                    <tr key={order.id}>
                      <td style={{fontWeight: 500, color: '#3b82f6'}}>{order.id}</td>
                      <td>{order.customer}</td>
                      <td>{order.date}</td>
                      <td>{order.amount}</td>
                      <td>
                        <span className={`status-badge status-${order.status.toLowerCase()}`}>
                          {order.status}
                        </span>
                      </td>
                      <td>
                        <button style={{background: 'none', border: 'none', color: '#6b7280', cursor: 'pointer'}}>View</button>
                      </td>
                    </tr>
                  ))}
                  {filteredSales.length === 0 && (
                    <tr>
                      <td colSpan="6" style={{textAlign: 'center', padding: '20px', color: '#6b7280'}}>No sales orders found.</td>
                    </tr>
                  )}
                </tbody>
              </table>
          </div>
        )}

        {activeMenu === 'Customers' && (
          <div className="data-table-container">
            <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: '20px' }}>
              <div style={{ position: 'relative' }}>
                <Search size={18} style={{ position: 'absolute', left: '10px', top: '50%', transform: 'translateY(-50%)', color: '#6b7280' }} />
                <input type="text" placeholder="Search customers..." style={{ padding: '8px 12px 8px 35px', borderRadius: '6px', border: '1px solid #d1d5db', outline: 'none' }} />
              </div>
              <button style={{ padding: '8px 16px', borderRadius: '6px', border: 'none', background: '#3b82f6', color: 'white', display: 'flex', alignItems: 'center', gap: '5px', cursor: 'pointer', fontWeight: 500 }}>
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
                    <td style={{fontWeight: 500, color: '#3b82f6'}}>{cust.id}</td>
                    <td>{cust.name}</td>
                    <td>{cust.email}</td>
                    <td>{cust.phone}</td>
                    <td>
                      <span className={`status-badge status-${cust.status.toLowerCase() === 'active' ? 'completed' : 'cancelled'}`}>
                        {cust.status}
                      </span>
                    </td>
                    <td>
                      <button style={{background: 'none', border: 'none', color: '#6b7280', cursor: 'pointer'}}>Edit</button>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        )}

        {activeMenu === 'Products' && (
          <div className="data-table-container">
            <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: '20px' }}>
              <div style={{ position: 'relative' }}>
                <Search size={18} style={{ position: 'absolute', left: '10px', top: '50%', transform: 'translateY(-50%)', color: '#6b7280' }} />
                <input type="text" placeholder="Search products..." style={{ padding: '8px 12px 8px 35px', borderRadius: '6px', border: '1px solid #d1d5db', outline: 'none' }} />
              </div>
              <button style={{ padding: '8px 16px', borderRadius: '6px', border: 'none', background: '#3b82f6', color: 'white', display: 'flex', alignItems: 'center', gap: '5px', cursor: 'pointer', fontWeight: 500 }}>
                <Plus size={18} /> Add Product
              </button>
            </div>
            <table className="data-table">
              <thead>
                <tr>
                  <th>Product ID</th>
                  <th>Name</th>
                  <th>Category</th>
                  <th>Price</th>
                  <th>Stock</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                {mockProducts.map((prod) => (
                  <tr key={prod.id}>
                    <td style={{fontWeight: 500, color: '#3b82f6'}}>{prod.id}</td>
                    <td>{prod.name}</td>
                    <td>{prod.category}</td>
                    <td>{prod.price}</td>
                    <td>
                      <span className={`status-badge status-${prod.stock > 20 ? 'completed' : 'pending'}`}>
                        {prod.stock} in stock
                      </span>
                    </td>
                    <td>
                      <button style={{background: 'none', border: 'none', color: '#6b7280', cursor: 'pointer'}}>Edit</button>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        )}

        {activeMenu === 'Settings' && (
          <div className="data-table-container" style={{maxWidth: '800px'}}>
            <h2 style={{marginBottom: '20px', borderBottom: '1px solid #e5e7eb', paddingBottom: '10px'}}>General Settings</h2>
            
            <div style={{display: 'flex', flexDirection: 'column', gap: '20px'}}>
              <div style={{display: 'flex', flexDirection: 'column', gap: '8px'}}>
                <label style={{fontWeight: 500, color: '#374151', fontSize: '14px'}}>Company Name</label>
                <input type="text" defaultValue="My ERP Portfolio" style={{padding: '10px', borderRadius: '6px', border: '1px solid #d1d5db', outline: 'none'}} />
              </div>
              
              <div style={{display: 'flex', flexDirection: 'column', gap: '8px'}}>
                <label style={{fontWeight: 500, color: '#374151', fontSize: '14px'}}>Support Email</label>
                <input type="email" defaultValue="admin@portfolio.com" style={{padding: '10px', borderRadius: '6px', border: '1px solid #d1d5db', outline: 'none'}} />
              </div>

              <div style={{display: 'flex', flexDirection: 'column', gap: '8px'}}>
                <label style={{fontWeight: 500, color: '#374151', fontSize: '14px'}}>Timezone</label>
                <select style={{padding: '10px', borderRadius: '6px', border: '1px solid #d1d5db', outline: 'none', backgroundColor: 'white'}}>
                  <option>Asia/Jakarta (GMT+7)</option>
                  <option>UTC (GMT+0)</option>
                  <option>America/New_York (GMT-5)</option>
                </select>
              </div>

              <div style={{display: 'flex', flexDirection: 'column', gap: '8px'}}>
                <label style={{fontWeight: 500, color: '#374151', fontSize: '14px'}}>Theme Preference</label>
                <div style={{display: 'flex', gap: '15px'}}>
                  <label style={{display: 'flex', alignItems: 'center', gap: '5px', fontSize: '14px'}}><input type="radio" name="theme" defaultChecked /> Light Theme</label>
                  <label style={{display: 'flex', alignItems: 'center', gap: '5px', fontSize: '14px'}}><input type="radio" name="theme" /> Dark Theme</label>
                </div>
              </div>

              <div style={{marginTop: '10px'}}>
                <button style={{ padding: '10px 20px', borderRadius: '6px', border: 'none', background: '#3b82f6', color: 'white', display: 'flex', alignItems: 'center', gap: '8px', cursor: 'pointer', fontWeight: 500 }}>
                  <Save size={18} /> Save Settings
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
