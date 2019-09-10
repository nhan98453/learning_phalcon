import React from 'react';
import { BrowserRouter as Router, Route, Link } from 'react-router-dom';
import Dropdown from 'react-dropdown';
import Brand from './Brand';
import Category from './Category';
import Product from './Product';
import Footer from './Footer';
export default class Content extends React.Component {
    constructor(props) {
        super(props);
        //this.handleClick = this.handleClick.bind(this);
        this.state = {
            active: "Product",
            brandList:JSON.parse(localStorage.getItem('brand')),
            categoryList:JSON.parse(localStorage.getItem('category')),
            productList:JSON.parse(localStorage.getItem('product')),
        }
    }
    handleClick = (value) => {
        this.setState({
            active: value.target.innerText
        });
    }
    getActive(value) {
        if (this.state.active == value)
            return "active";
        return '';
    }
    render() {
        return (
            <div>
                <Router>
                    <div style={{ border: "2px solid red" }}>
                        <nav style={{ backgroundColor: "#cccccc" }}>
                            <div >
                                <ul >
                                    <li><Link to="/" href="#" onClick={e => this.handleClick(e)}>Home</Link></li>
                                    <li><Link to="/product" href="#" onClick={e => this.handleClick(e)}>Product</Link></li>
                                    <li><Link to="/category" href="#" onClick={e => this.handleClick(e)}>Category</Link></li>
                                    <li><Link to="/brand" href="#" onClick={e => this.handleClick(e)}>Brand</Link></li>
                                </ul>
                            </div>
                        </nav>
                        <Dropdown
                        options={this.state.categoryList}
                        />
                        <Route exact path="/" component={Home} />
                        <Route path="/product" component={Product} />
                        <Route path="/category" component={Category} />
                        <Route path="/brand" component={Brand} />
                    </div>
                </Router>

                <Footer />
            </div>

        )
    }
}
class Home extends React.Component {
    render() {
        return (
            <div>

            </div>
        )
    }
}
