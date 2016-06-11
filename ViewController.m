//
//  ViewController.m
//  xIdent_Browser
//
//  Created by Moritz Beck on 11/06/16.
//  Copyright © 2016 Moritz Beck. All rights reserved.
//

#import "ViewController.h"
#import <WebKit/WebKit.h>

@interface ViewController ()
@property (retain, nonatomic) WKWebView *webView;

@end
UIProgressView *viewd;

@implementation ViewController

- (void)viewDidLoad {
    [super viewDidLoad];
    // Do any additional setup after loading the view, typically from a nib.
    
    _webView = [[WKWebView alloc]initWithFrame:CGRectMake(0,self.view.frame.size.height/10,self.view.frame.size.width,self.view.frame.size.height-(self.view.frame.size.height/10))];
    
    
    [self.view addSubview:_webView];
    
    [_webView loadRequest:[NSURLRequest requestWithURL:[NSURL URLWithString:(@"http://xident.tk/test.php?u=http://duckduckgo.de")]]];
    _webView.navigationDelegate = (id)self;
    
    _webView.allowsBackForwardNavigationGestures = YES;
    
    
    viewd = [[UIProgressView alloc]initWithFrame:CGRectMake(0, 0, self.view.frame.size.width, 40)];
    [self.view addSubview:viewd];
    [_webView addObserver:self forKeyPath:NSStringFromSelector(@selector(estimatedProgress)) options:NSKeyValueObservingOptionNew context:NULL];
    
}
- (void)observeValueForKeyPath:(NSString *)keyPath ofObject:(id)object change:(NSDictionary *)change context:(void *)context {
    if ([keyPath isEqualToString:NSStringFromSelector(@selector(estimatedProgress))] && object == self.webView) {
        NSLog(@"%f", self.webView.estimatedProgress);
        // estimatedProgress is a value from 0.0 to 1.0
        // Update your UI here accordingly
        viewd.progress = self.webView.estimatedProgress;
        
    }
    else {
        // Make sure to call the superclass's implementation in the else block in case it is also implementing KVO
        [super observeValueForKeyPath:keyPath ofObject:object change:change context:context];
    }
}

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

@end
