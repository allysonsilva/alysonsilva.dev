@extends('layouts.default')

@section('content')
    <section class='container-inner'>
            <header>
                <h2>Elements</h2>
                <p>Just an assorted selection of elements.</p>
            </header>

            <!-- Text -->
            <section>
                <h3>Text</h3>
                <p>This is <b>bold</b> and this is <strong>strong</strong>. This is <i>italic</i> and this is <em>emphasized</em>. This is <sup>superscript</sup> text and this is <sub>subscript</sub> text. This is <u>underlined</u> and this is code: <code>for (;;) { ... }</code>. Finally, <a href="#">this is a link</a>.</p>
                <hr>
                <header>
                    <h3>Heading with a Subtitle</h3>
                    <p>Lorem ipsum dolor sit amet nullam id egestas urna aliquam</p>
                </header>
                <p>Nunc lacinia ante nunc ac lobortis. Interdum adipiscing gravida odio porttitor sem non mi integer non faucibus ornare mi ut ante amet placerat aliquet. Volutpat eu sed ante lacinia sapien lorem accumsan varius montes viverra nibh in adipiscing blandit tempus accumsan.</p>
                <header>
                    <h4>Heading with a Subtitle</h4>
                    <p>Lorem ipsum dolor sit amet nullam id egestas urna aliquam</p>
                </header>
                <p>Nunc lacinia ante nunc ac lobortis. Interdum adipiscing gravida odio porttitor sem non mi integer non faucibus ornare mi ut ante amet placerat aliquet. Volutpat eu sed ante lacinia sapien lorem accumsan varius montes viverra nibh in adipiscing blandit tempus accumsan.</p>
                <hr>
                <h1>Heading Level 1</h1>
                <h2>Heading Level 2</h2>
                <h3>Heading Level 3</h3>
                <h4>Heading Level 4</h4>
                <h5>Heading Level 5</h5>
                <h6>Heading Level 6</h6>
                <hr>
                <h4>Blockquote</h4>
                <blockquote>Fringilla nisl. Donec accumsan interdum nisi, quis tincidunt felis sagittis eget tempus euismod. Vestibulum ante ipsum primis in faucibus vestibulum. Blandit adipiscing eu felis iaculis volutpat ac adipiscing accumsan faucibus. Vestibulum ante ipsum primis in faucibus lorem ipsum dolor sit amet nullam adipiscing eu felis.</blockquote>
                <blockquote><p>Fringilla nisl. Donec accumsan interdum nisi, quis tincidunt felis sagittis eget tempus euismod. Vestibulum ante ipsum primis in faucibus vestibulum. Blandit adipiscing eu felis iaculis volutpat ac adipiscing accumsan faucibus. Vestibulum ante ipsum primis in faucibus lorem ipsum dolor sit amet nullam adipiscing eu felis.</p></blockquote>
                <h4>Preformatted</h4>
                <pre><code>i = 0;
    while (!deck.isInOrder()) {
        print 'Iteration ' + i;
        deck.shuffle();
        i++;
    }

    print 'It took ' + i + ' iterations to sort the deck.';</code></pre>
            </section>

            <!-- Buttons -->
            <section class="box-old">
                <h3>Buttons</h3>
                <ul class="actions">
                    <li><a href="#" class="btn without-style primary">Primary</a></li>
                    <li><a href="#" class="btn without-style">Default</a></li>
                    <li><a href="#" class="btn without-style light">Alternate</a></li>
                </ul>
                <ul class="actions">
                    <li><a href="#" class="btn without-style large">Large</a></li>
                    <li><a href="#" class="btn without-style medium">Medium</a></li>
                    <li><a href="#" class="btn without-style">Default</a></li>
                    <li><a href="#" class="btn without-style light small">Small</a></li>
                </ul>
                <ul class="actions fit">
                    <li><a href="#" class="btn without-style fit large">Fit</a></li>
                    <li><a href="#" class="btn without-style fit medium">Fit</a></li>
                    <li><a href="#" class="btn without-style fit">Fit</a></li>
                    <li><a href="#" class="btn without-style light fit small">Fit</a></li>
                </ul>
                <ul class="actions fit small">
                    <li><a href="#" class="btn without-style fit small">Fit + Small</a></li>
                    <li><a href="#" class="btn without-style fit small">Fit + Small</a></li>
                    <li><a href="#" class="btn without-style light fit small">Fit + Small</a></li>
                </ul>
                <ul class="actions">
                    <li><a href="#" class="btn without-style icon solid fa-search primary">Icon</a></li>
                    <li><a href="#" class="btn without-style icon solid fa-home large">Icon</a></li>
                    <li><a href="#" class="btn without-style icon solid fa-download medium">Icon</a></li>
                    <li><a href="#" class="btn without-style light icon solid fa-check">Icon</a></li>
                    <li><a href="#" class="btn without-style light icon solid fa-link small">Icon</a></li>
                </ul>
                <div>
                    <a href="#" class="btn without-style btn--light btn--full-width">btn--light</a>
                </div>
                <ul class="actions">
                    <li><span class="btn without-style disabled">Special</span></li>
                    <li><span class="btn without-style disabled">Default</span></li>
                    <li><span class="btn without-style light disabled">Alternate</span></li>
                </ul>
            </section>

            <!-- Form -->
            <section class="box2">
                <h3 class='box__title'>Forms</h3>
                <form method="post" action="#">
                    <div class="row gtr-uniform gtr-50">
                        <div class="col-6 col-12-mobilep">
                            <input type="text" name="name" id="name" value="" placeholder="Name">
                        </div>
                        <div class="col-6 col-12-mobilep">
                            <input type="email" name="email" id="email" value="" placeholder="Email">
                        </div>
                        <div class="col-12">
                            <select name="category" id="category">
                                <option value="">- Category -</option>
                                <option value="1">Manufacturing</option>
                                <option value="1">Shipping</option>
                                <option value="1">Administration</option>
                                <option value="1">Human Resources</option>
                            </select>
                        </div>
                        <div class="col-4 col-12-narrower">
                            <input type="radio" id="priority-low" name="priority" checked="">
                            <label for="priority-low">Low Priority</label>
                        </div>
                        <div class="col-4 col-12-narrower">
                            <input type="radio" id="priority-normal" name="priority">
                            <label for="priority-normal">Normal Priority</label>
                        </div>
                        <div class="col-4 col-12-narrower">
                            <input type="radio" id="priority-high" name="priority">
                            <label for="priority-high">High Priority</label>
                        </div>
                        <div class="col-6 col-12-narrower">
                            <input type="checkbox" id="copy" name="copy">
                            <label for="copy">Email me a copy of this message</label>
                        </div>
                        <div class="col-6 col-12-narrower">
                            <input type="checkbox" id="human" name="human" checked="">
                            <label for="human">I am a human and not a robot</label>
                        </div>
                        <div class="col-12">
                            <textarea name="message" id="message" placeholder="Enter your message" rows="6"></textarea>
                        </div>
                        <div class="col-12">
                            <ul class="actions">
                                <li><input type="submit" value="Send Message"></li>
                                <li><input type="reset" value="Reset" class="light"></li>
                            </ul>
                        </div>
                    </div>
                </form>
                <hr>

                <form method="post" action="#">
                    <div class="row gtr-uniform gtr-50">
                        <div class="col-9 col-12-mobilep">
                            <input type="text" name="query" id="query" value="" placeholder="Query">
                        </div>
                        <div class="col-3 col-12-mobilep">
                            <input type="submit" value="Search" class="fit">
                        </div>
                    </div>
                </form>
            </section>

            <!-- Lists -->
            <section>
                <h3>Lists</h3>
                <div class="row">
                    <div class="col-6 col-12-xsmall">
                        <h4>Unordered</h4>
                        <ul>
                            <li>Dolor pulvinar etiam magna etiam.</li>
                            <li>Sagittis adipiscing lorem eleifend.</li>
                            <li>Felis enim feugiat dolore viverra.</li>
                        </ul>

                        <h4>Alternate</h4>
                        <ul class="alternate">
                            <li>Dolor pulvinar etiam magna etiam.</li>
                            <li>Sagittis adipiscing lorem eleifend.</li>
                            <li>Felis enim feugiat dolore viverra.</li>
                            <li>Lobortis adipiscing condimentum lorem.</li>
                            <li>Integer eleifend erat sed accumsan.</li>
                        </ul>
                    </div>

                    <div class="col-6 col-12-xsmall">
                        <h4>Ordered</h4>
                        <ol>
                            <li>Dolor pulvinar etiam magna etiam.</li>
                            <li>Etiam vel felis at lorem sed viverra.</li>
                            <li>Felis enim feugiat dolore viverra.</li>
                            <li>Dolor pulvinar etiam magna etiam.</li>
                            <li>Etiam vel felis at lorem sed viverra.</li>
                            <li>Felis enim feugiat dolore viverra.</li>
                        </ol>

                        <h4>Icons</h4>
                        <ul class="icons space-between">
                            <li><a href="#" class="icon brands fa-laravel without-style color-accent"><span class="label">fa-laravel</span></a></li>
                            <li><a href="#" class="icon brands fa-php without-style color-accent"><span class="label">fa-php</span></a></li>
                            <li><a href="#" class="icon fa-apple-alt without-style color-accent"><span class="label">fa-apple-alt</span></a></li>
                            <li><a href="#" class="icon brands fa-aws without-style color-accent"><span class="label">fa-aws</span></a></li>
                            <li><a href="#" class="icon brands fa-docker without-style color-accent"><span class="label">fa-docker</span></a></li>
                        </ul>
                        <ul class="icons">
                            <li><a href="#" class="icon light brands fa-laravel without-style"><span class="label">fa-laravel</span></a></li>
                            <li><a href="#" class="icon light brands fa-php without-style"><span class="label">fa-php</span></a></li>
                            <li><a href="#" class="icon light fa-apple-alt without-style"><span class="label">fa-apple-alt</span></a></li>
                            <li><a href="#" class="icon light brands fa-aws without-style"><span class="label">fa-aws</span></a></li>
                            <li><a href="#" class="icon light brands fa-docker without-style"><span class="label">fa-docker</span></a></li>
                        </ul>
                    </div>
                </div>

                <h4>Actions</h4>
                <ul class="actions centralize">
                    <li><a href="#" class="without-style btn primary">Default</a></li>
                    <li><a href="#" class="without-style btn">Default</a></li>
                    <li><a href="#" class="without-style btn alt">Default</a></li>
                </ul>
                <ul class="actions">
                    <li><a href="#" class="without-style btn primary small">Small</a></li>
                    <li><a href="#" class="without-style btn small">Small</a></li>
                    <li><a href="#" class="without-style btn light small">Small</a></li>
                </ul>
                <div class="row">
                    <div class="col-3 col-6-narrower col-12-mobilep">
                        <ul class="actions stacked">
                            <li><a href="#" class="without-style btn primary">Default</a></li>
                            <li><a href="#" class="without-style btn">Default</a></li>
                            <li><a href="#" class="without-style btn light">Default</a></li>
                        </ul>
                    </div>
                    <div class="col-3 col-6-narrower col-12-mobilep">
                        <ul class="actions stacked">
                            <li><a href="#" class="without-style btn primary small">Small</a></li>
                            <li><a href="#" class="without-style btn small">Small</a></li>
                            <li><a href="#" class="without-style btn light small">Small</a></li>
                        </ul>
                    </div>
                    <div class="col-3 col-6-narrower col-12-mobilep">
                        <ul class="actions fit stacked">
                            <li><a href="#" class="without-style btn primary fit">Default</a></li>
                            <li><a href="#" class="without-style btn fit">Default</a></li>
                            <li><a href="#" class="without-style btn light fit">Default</a></li>
                        </ul>
                    </div>
                    <div class="col-3 col-6-narrower col-12-mobilep">
                        <ul class="actions stacked">
                            <li><a href="#" class="without-style btn primary small fit">Small</a></li>
                            <li><a href="#" class="without-style btn small fit">Small</a></li>
                            <li><a href="#" class="without-style btn light small fit">Small</a></li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- Table -->
            <div class="row">
                <div class="col-12">
                    <section class="box-no">
                        <h3>Table</h3>

                        <h4>Default</h4>
                        <div class="table-wrapper">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Something</td>
                                        <td>Ante turpis integer aliquet porttitor.</td>
                                        <td>29.99</td>
                                    </tr>
                                    <tr>
                                        <td>Nothing</td>
                                        <td>Vis ac commodo adipiscing arcu aliquet.</td>
                                        <td>19.99</td>
                                    </tr>
                                    <tr>
                                        <td>Something</td>
                                        <td> Morbi faucibus arcu accumsan lorem.</td>
                                        <td>29.99</td>
                                    </tr>
                                    <tr>
                                        <td>Nothing</td>
                                        <td>Vitae integer tempus condimentum.</td>
                                        <td>19.99</td>
                                    </tr>
                                    <tr>
                                        <td>Something</td>
                                        <td>Ante turpis integer aliquet porttitor.</td>
                                        <td>29.99</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2"></td>
                                        <td>100.00</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <h4>Alternate</h4>
                        <div class="table-wrapper">
                            <table class="alternate">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Something</td>
                                        <td>Ante turpis integer aliquet porttitor.</td>
                                        <td>29.99</td>
                                    </tr>
                                    <tr>
                                        <td>Nothing</td>
                                        <td>Vis ac commodo adipiscing arcu aliquet.</td>
                                        <td>19.99</td>
                                    </tr>
                                    <tr>
                                        <td>Something</td>
                                        <td> Morbi faucibus arcu accumsan lorem.</td>
                                        <td>29.99</td>
                                    </tr>
                                    <tr>
                                        <td>Nothing</td>
                                        <td>Vitae integer tempus condimentum.</td>
                                        <td>19.99</td>
                                    </tr>
                                    <tr>
                                        <td>Something</td>
                                        <td>Ante turpis integer aliquet porttitor.</td>
                                        <td>29.99</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2"></td>
                                        <td>100.00</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </section>
                </div>
            </div>
        </section>
@endsection
