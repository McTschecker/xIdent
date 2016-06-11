
//
//  AppDelegate.m
//  VertretungsPlan
//
//  Created by Jonathan Lucas Fritz on 20.02.16.
//  Copyright © 2016 NOSCIO. All rights reserved.
//

#import "AppDelegate.h"
#import "Mixpanel.h"
#import <Google/CloudMessaging.h>
#define MIXPANEL_TOKEN @"CENSORED"
#import <Google/Analytics.h>
#import "NosNotify.h"


@interface AppDelegate ()
@property(nonatomic, strong) void (^registrationHandler)
(NSString *registrationToken, NSError *error);
@property(nonatomic, assign) BOOL connectedToGCM;
@property(nonatomic, strong) NSString* registrationToken;
@property(nonatomic, assign) BOOL subscribedToTopic;

@end

@implementation AppDelegate
NosNotify *closer;


- (BOOL)application:(UIApplication *)application didFinishLaunchingWithOptions:(NSDictionary *)launchOptions {
        

    
    return YES;
}


- (void)applicationDidBecomeActive:(UIApplication *)application {


}

-(void)application:(UIApplication *)application didReceiveRemoteNotification:(NSDictionary *)userInfo
{

}

- (void)applicationWillResignActive:(UIApplication *)application {
    // Sent when the application is about to move from active to inactive state. This can occur for certain types of temporary interruptions (such as an incoming phone call or SMS message) or when the user quits the application and it begins the transition to the background state.
    // Use this method to pause ongoing tasks, disable timers, and throttle down OpenGL ES frame rates. Games should use this method to pause the game.
}

- (void)applicationDidEnterBackground:(UIApplication *)application
{
    
}

- (void)applicationWillEnterForeground:(UIApplication *)application {
    // Called as part of the transition from the background to the inactive state; here you can undo many of the changes made on entering the background.
}

- (void)applicationWillTerminate:(UIApplication *)application {
    // Called when the application is about to terminate. Save data if appropriate. See also applicationDidEnterBackground:.
}

@end
